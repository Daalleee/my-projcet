<?php

namespace App\Filament\Resources\MotorResource\Pages;

use App\Filament\Resources\MotorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMotor extends CreateRecord
{
    protected static string $resource = MotorResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
