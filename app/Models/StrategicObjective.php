<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StrategicObjective extends Model
{
    protected $fillable = [
        'name',
    ];

    public function odds(): BelongsToMany
    {
        return $this->BelongsToMany(OperationalObjective::class);
    }
}
