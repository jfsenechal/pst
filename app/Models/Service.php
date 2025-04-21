<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;

class Service extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'initials',
        'department',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function leadingActions(): BelongsToMany
    {
        return $this->belongsToMany(Action::class, 'action_service_leader');
    }

    public function partneringActions(): BelongsToMany
    {
        return $this->belongsToMany(Action::class, 'action_service_partner');
    }

}
