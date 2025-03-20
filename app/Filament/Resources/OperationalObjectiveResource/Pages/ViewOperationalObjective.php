<?php

namespace App\Filament\Resources\OperationalObjectiveResource\Pages;

use App\Filament\Resources\OperationalObjectiveResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewOperationalObjective extends ViewRecord
{
    protected static string $resource = OperationalObjectiveResource::class;

    public function getTitle(): string
    {
        return $this->record->name ?? 'Empty name';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
