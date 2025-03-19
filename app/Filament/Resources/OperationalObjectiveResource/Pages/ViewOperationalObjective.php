<?php

namespace App\Filament\Resources\OperationalObjectiveResource\Pages;

use App\Filament\Resources\OperationalObjectiveResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewOperationalObjective extends ViewRecord
{
    protected static string $resource = OperationalObjectiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
