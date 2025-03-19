<?php

namespace App\Filament\Resources\StrategicObjectiveResource\Pages;

use App\Filament\Resources\StrategicObjectiveResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStrategicObjective extends ViewRecord
{
    protected static string $resource = StrategicObjectiveResource::class;

    public function getTitle(): string
    {
        return $this->record->name ?? 'Empty name';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
