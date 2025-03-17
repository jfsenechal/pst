<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ActionAgent extends Model
{
    public function action(): BelongsTo
    {
        return $this->belongsTo(Action::class);//belong id set here
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);//belong id set here
    }
}
