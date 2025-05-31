<?php

namespace App\Filament\Kasir\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Rental;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Kasir\Resources\RentalResource\Pages;

class RentalResource extends Resource
{
    protected static ?string $model = Rental::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Daftar Sewa';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('status')
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
            TextColumn::make('user.name')->label('Penyewa'),
            ImageColumn::make('motor.image')
                ->label('Foto Motor')
                ->circular()
                ->height(60),
            TextColumn::make('motor.model')->label('Motor'),
            TextColumn::make('motor.plate_number')
                ->label('PLat'),
            TextColumn::make('start_date')->label('Mulai')->date(),
            TextColumn::make('end_date')->label('Selesai')->date(),
            TextColumn::make('lama_sewa')
                ->label('Durasi')
                ->getStateUsing(function ($record) {
                    $start = \Carbon\Carbon::parse($record->start_date);
                    $end = \Carbon\Carbon::parse($record->end_date);
                    return $start->diffInDays($end) + 1 . ' hari';
                }),
            TextColumn::make('total_price')->label('Total Harga')->money('IDR', true),
            BadgeColumn::make('status')
                ->label('Status')
                ->colors([
                    'primary' => fn($state) => $state === 'pending',
                    'success' => fn($state) => $state === 'confirmed',
                    'warning' => fn($state) => $state === 'completed',
                    'danger' => fn($state) => $state === 'cancelled',
                ])
                ->formatStateUsing(fn($state) => match ($state) {
                    'pending' => 'Menunggu',
                    'confirmed' => 'Dibayar',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                    default => ucfirst($state),
                })


        ])

            ->filters([
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'pending' => 'Menunggu',
                        'confirmed' => 'Dibayar',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ])
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('dibatalkan')
                    ->label('Dibatalkan')
                    ->action(function ($record) {
                        $record->status = 'cancelled';
                        $record->save();
                    })
                    ->visible(fn($record) => $record->status === 'pending')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle'),

                Tables\Actions\Action::make('konfirmasi')
                    ->label('Konfirmasi')
                    ->action(function ($record) {
                        $record->status = 'confirmed';
                        $record->save();
                    })
                    ->visible(fn($record) => $record->status === 'pending')
                    ->color('success')
                    ->icon('heroicon-o-check-circle'),

                Tables\Actions\Action::make('selesai')
                    ->label('Selesai')
                    ->action(function ($record) {
                        $record->status = 'completed';
                        $record->save();
                    })
                    ->visible(fn($record) => $record->status === 'confirmed') // hanya muncul jika sudah dikonfirmasi
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
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}
