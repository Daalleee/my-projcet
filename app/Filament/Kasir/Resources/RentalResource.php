<?php

namespace App\Filament\Kasir\Resources;

use App\Filament\Kasir\Resources\RentalResource\Pages;
use App\Models\Rental;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;

class RentalResource extends Resource
{
    protected static ?string $model = Rental::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Daftar Sewa';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('status')
                ->label('Status Pembayaran')
                ->options([
                    'pending' => 'Menunggu',
                    'confirmed' => 'Dibayar',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('user.name')->label('Penyewa')->sortable()->searchable(),
            ImageColumn::make('motor.image')->label('Foto Motor')->circular()->height(60),
            TextColumn::make('motor.brand')->label('Merek')->sortable(),
            TextColumn::make('motor.model')->label('Model')->sortable(),
            TextColumn::make('motor.plate_number')->label('Plat')->sortable(),
            TextColumn::make('start_date')->label('Mulai')->date()->sortable(),
            TextColumn::make('end_date')->label('Selesai')->date()->sortable(),
            TextColumn::make('lama_sewa')->label('Durasi')->getStateUsing(function ($record) {
                return \Carbon\Carbon::parse($record->start_date)->diffInDays(\Carbon\Carbon::parse($record->end_date)) + 1 . ' hari';
            }),
            TextColumn::make('total_price')->label('Total Harga')->money('IDR', true)->sortable(),
            BadgeColumn::make('status')->label('Status')->colors([
                'primary' => 'pending',
                'success' => 'confirmed',
                'warning' => 'completed',
                'danger' => 'cancelled',
            ])->formatStateUsing(fn($state) => match ($state) {
                'pending' => 'Menunggu',
                'confirmed' => 'Dibayar',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan',
                default => ucfirst($state),
            }),
        ])
            ->filters([
                SelectFilter::make('status')->label('Filter Status')->options([
                    'pending' => 'Menunggu',
                    'confirmed' => 'Dibayar',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                ]),
            ])
            ->actions([
                Tables\Actions\Action::make('konfirmasi')
                    ->label('Konfirmasi')
                    ->action(function ($record) {
                        $record->status = 'confirmed';
                        $record->motor->status = 'rented'; // Ubah status motor
                        $record->motor->save();
                        $record->save();
                    })
                    ->visible(fn($record) => $record->status === 'pending')
                    ->color('success')
                    ->icon('heroicon-o-check-circle'),
                Tables\Actions\Action::make('dibatalkan')
                    ->label('Dibatalkan')
                    ->action(function ($record) {
                        $record->status = 'cancelled';
                        $record->motor->status = 'available'; // Kembalikan status motor
                        $record->motor->save();
                        $record->save();
                    })
                    ->visible(fn($record) => $record->status === 'pending')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle'),
                Tables\Actions\Action::make('selesai')
                    ->label('Selesai')
                    ->action(function ($record) {
                        $record->status = 'completed';
                        $record->motor->status = 'available'; // Kembalikan status motor
                        $record->motor->save();
                        $record->save();
                    })
                    ->visible(fn($record) => $record->status === 'confirmed')
                    ->color('warning')
                    ->icon('heroicon-o-check-circle'),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRentals::route('/'),
            'edit' => Pages\EditRental::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Kasir tidak bisa buat sewa manual
    }

    public static function canDeleteAny(): bool
    {
        return false; // Tidak boleh hapus sewa
    }
}
