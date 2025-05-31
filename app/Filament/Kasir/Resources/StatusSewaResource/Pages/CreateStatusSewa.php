<?php

namespace App\Filament\Kasir\Resources\StatusSewaResource\Pages;

use App\Filament\Kasir\Resources\StatusSewaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStatusSewa extends CreateRecord
{
    protected static string $resource = StatusSewaResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
