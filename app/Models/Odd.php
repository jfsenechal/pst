<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * objectif de développement durable
 */
class Odd extends Model
{
    protected $fillable = [
        'action_id',
        'name',
        'justification',
        'description',
        'idImport',
    ];

    /**
     * Get the action that owns the comment.
     */
    public function action(): BelongsTo
    {
        return $this->belongsTo(Action::class);
    }
}
