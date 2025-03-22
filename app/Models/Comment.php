<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = [
        'content',
    ];

    /**
     * Get the action that owns the comment.
     */
    public function action(): BelongsTo
    {
        return $this->belongsTo(Action::class);
    }
}
