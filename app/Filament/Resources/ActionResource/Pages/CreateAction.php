<?php

namespace App\Filament\Resources\ActionResource\Pages;

use App\Filament\Resources\ActionResource;
use App\Models\OperationalObjective;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateAction extends CreateRecord
{
    protected static string $resource = ActionResource::class;

    /**
     * to set department
     */
    protected function handleRecordCreation(array $data): Model
    {
        $record = new ($this->getModel())($data);

        if (
            static::getResource()::isScopedToTenant() &&
            ($tenant = Filament::getTenant())
        ) {
            return $this->associateRecordWithTenant($record, $tenant);
        }

        $operationalObjective = OperationalObjective::find($record->operational_objective_id);
        $record->department = $operationalObjective?->department;
        $record->save();

        return $record;
    }

}
