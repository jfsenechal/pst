<?php

namespace App\Filament\Resources\OperationalObjectiveResource\Pages;

use App\Filament\Resources\OperationalObjectiveResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOperationalObjective extends EditRecord
{
    protected static string $resource = OperationalObjectiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->icon('tabler-eye'),
        ];
    }
}
