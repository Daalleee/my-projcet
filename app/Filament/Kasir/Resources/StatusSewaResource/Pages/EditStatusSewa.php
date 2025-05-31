<?php

namespace App\Filament\Kasir\Resources\StatusSewaResource\Pages;

use App\Filament\Kasir\Resources\StatusSewaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStatusSewa extends EditRecord
{
    protected static string $resource = StatusSewaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
