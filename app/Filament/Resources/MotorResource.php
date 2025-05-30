<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Motor;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\MotorResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MotorResource\RelationManagers;

class MotorResource extends Resource
{
    protected static ?string $model = Motor::class;

    protected static ?string $navigationLabel = 'Kelola Motor';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('brand')
                    ->required(),
                TextInput::make('model')
                    ->required(),
                TextInput::make('plate_number')
                    ->required(),
                TextInput::make('description')
                    ->required(),
                TextInput::make('rental_price_per_day')
                    ->label('Harga/Hari')
                    ->numeric()
                    ->required(),
                Select::make('status')
                    ->options([
                        'available' => 'Tersedia',
                        'rented' => 'Disewa',
                        'maintenance' => 'Perawatan',
                    ])
                    ->required(),

                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->directory('motor-images')
                    ->disk('public')
                    ->label('foto motor')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Foto Unit')
                    ->disk('public')
                    ->label('Foto')
                    ->height(50),


                TextColumn::make('brand')
                    ->label('Merk'),
                TextColumn::make('model'),
                TextColumn::make('plate_number')
                    ->label('Plat'),
                TextColumn::make('description')
                    ->label('Deskripsi'),

                TextColumn::make('rental_price_per_day')
                    ->label('Harga/hari')
                    ->money('IDR'),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'available' => 'Tersedia',
                        'rented' => 'Disewa',
                        'maintenance' => 'Perawatan',
                    })

            ])

            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListMotors::route('/'),
            'create' => Pages\CreateMotor::route('/create'),
            'edit' => Pages\EditMotor::route('/{record}/edit'),
        ];
    }
}
