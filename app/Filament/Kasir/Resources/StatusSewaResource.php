<?php

namespace App\Filament\Kasir\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Rental;
use Filament\Forms\Form;
use App\Models\StatusSewa;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Kasir\Resources\StatusSewaResource\Pages;
use App\Filament\Kasir\Resources\StatusSewaResource\RelationManagers;

class StatusSewaResource extends Resource
{

    protected static ?string $model = Rental::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Status Sewa';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Pelanggan'),
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
                        $end = Carbon::parse($record->end_date);
                        return $start->diffInDays($end) + 1 . ' hari';
                    }),
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
                        'confirmed' => 'Aktif',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default => ucfirst($state),
                    })
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStatusSewas::route('/'),
            'create' => Pages\CreateStatusSewa::route('/create'),
            'edit' => Pages\EditStatusSewa::route('/{record}/edit'),
        ];
    }
    public static function canCreate(): bool
    {
        return false;
    }
}
