<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Action extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'users',
        'partners',
        'services',
        'progress_indicator',
        'due_date',
        'description',
        'evaluation_indicator',
        'work_plan',
        'budget_estimate',
        'financing_mode',
        'operational_objective_id'
    ];

    /**
     * Get the operational objective that owns the action.
     */
    public function operationalObjective(): BelongsTo
    {
        return $this->belongsTo(OperationalObjective::class);
    }

    /**
     * @return BelongsToMany<Service>
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }

    /**
     * @return BelongsToMany<User>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return BelongsToMany<Partner>
     */
    public function partners(): BelongsToMany
    {
        return $this->belongsToMany(Partner::class);
    }

    /**
     * Get the comments for the action.
     * @return HasMany<Comment>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

}
