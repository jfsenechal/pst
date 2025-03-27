<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * objectif de développement durable
 */
class Odd extends Model
{
    protected $fillable = [
        'name',
        'justification',
        'description',
    ];

    /**
     * @return BelongsToMany<Action>
     */
    public function actions(): BelongsToMany
    {
        return $this->belongsToMany(Action::class);
    }
}
