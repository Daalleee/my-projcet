<?php

namespace App\Filament\Kasir\Resources\RentalResource\Pages;

use App\Filament\Kasir\Resources\RentalResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRental extends CreateRecord
{
    protected static string $resource = RentalResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
