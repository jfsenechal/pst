<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StrategicObjective extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * Get the operational objectives for the strategic objective.
     * @return HasMany<Comment>
     */
    public function odds(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
