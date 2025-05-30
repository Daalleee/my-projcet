<?php

namespace App\Filament\Kasir\Resources\StatusSewaResource\Pages;

use App\Filament\Kasir\Resources\StatusSewaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStatusSewas extends ListRecords
{
    protected static string $resource = StatusSewaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
