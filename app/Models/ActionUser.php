<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActionUser extends Model
{
    public function action(): BelongsTo
    {
        return $this->belongsTo(Action::class);//belong id set here
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);//belong id set here
    }
}
