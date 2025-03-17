<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OperationalObjective extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * @return BelongsToMany<StrategicObjective>
     */
    public function strategicObjectives(): BelongsToMany
    {
        return $this->belongsToMany(StrategicObjective::class);
    }

    public function actions(): BelongsToMany
    {
        return $this->BelongsToMany(Action::class);
    }
}
