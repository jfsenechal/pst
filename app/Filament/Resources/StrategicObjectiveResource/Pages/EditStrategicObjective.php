<?php

namespace App\Filament\Resources\StrategicObjectiveResource\Pages;

use App\Filament\Resources\StrategicObjectiveResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStrategicObjective extends EditRecord
{
    protected static string $resource = StrategicObjectiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->icon('tabler-eye'),
        ];
    }

    //force remove when edit
    public function getRelationManagers(): array
    {
        return [];
    }

}
