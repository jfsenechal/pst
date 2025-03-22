<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Odd extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * Get the action that owns the comment.
     */
    public function action(): BelongsTo
    {
        return $this->belongsTo(Action::class);
    }
}
