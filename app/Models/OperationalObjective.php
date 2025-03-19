<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class OperationalObjective extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
    ];

    /**
     * Get the strategic objective that owns the operational objective.
     */
    public function strategicObjective(): BelongsTo
    {
        return $this->belongsTo(StrategicObjective::class);
    }

    /**
     * Get the actions for the Operational Objective.
     * @return HasMany<OperationalObjective>
     */
    public function actions(): HasMany
    {
        return $this->hasMany(OperationalObjective::class);
    }

}
