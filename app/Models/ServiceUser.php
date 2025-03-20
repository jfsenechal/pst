<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceUser extends Model
{
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);//belong id set here
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);//belong id set here
    }
}
