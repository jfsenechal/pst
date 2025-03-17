<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ActionComment extends Model
{
    public function action(): BelongsTo
    {
        return $this->belongsTo(Action::class);//belong id set here
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);//belong id set here
    }
}
