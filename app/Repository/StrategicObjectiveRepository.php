<?php

namespace App\Repository;

use App\Models\StrategicObjective;
use Illuminate\Support\Collection;

class StrategicObjectiveRepository
{
    /**
     * @return Collection|StrategicObjective[]
     */
    public static function findAllWithOosAndActions(): Collection
    {
        return StrategicObjective::with('oos.actions')
            ->orderBy('position')
            ->get();

    }
}
