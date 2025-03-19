<?php

namespace App\Filament\Resources\StrategicObjectiveResource\Pages;

use App\Filament\Resources\StrategicObjectiveResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStrategicObjectives extends ListRecords
{
    protected static string $resource = StrategicObjectiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
