<?php

namespace App\Models;

use App\Constant\ActionStateEnum;
use App\Constant\ActionTypeEnum;
use App\Observers\ActionObserver;
use App\Repository\UserRepository;
use Auth;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Notifications\Notifiable;

#[ObservedBy([ActionObserver::class])]
class Action extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'state',
        'state_percentage',
        'type',
        'roadmap',
        'note',
        'department',
        'due_date',
        'description',
        'evaluation_indicator',
        'work_plan',
        'budget_estimate',
        'financing_mode',
        'operational_objective_id',
        'user_add',
    ];

    protected $casts = [
        'medias' => 'array',
        'due_date' => 'datetime',
        'state' => ActionStateEnum::class,
        'type' => ActionTypeEnum::class,
    ];

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (Auth::check()) {
                $user = Auth::user();
                $model->user_add = $user->username;
            }
        });
    }

    /**
     * Get the operational objective that owns the action.
     */
    public function operationalObjective(): BelongsTo
    {
        return $this->belongsTo(OperationalObjective::class);
    }

    public function linkedActions(): BelongsToMany
    {
        return $this->belongsToMany(
            Action::class,
            'action_related',
            'action_id',
            'related_action_id'
        );
    }

    /**
     * @return BelongsToMany<Service>
     */
    public function leaderServices(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'action_service_leader');
    }

    /**
     * @return BelongsToMany<Service>
     */
    public function partnerServices(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'action_service_partner');
    }

    /**
     * @return BelongsToMany<User>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function mandataries(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'action_mandatory');
    }

    /**
     * @return BelongsToMany<Partner>
     */
    public function partners(): BelongsToMany
    {
        return $this->belongsToMany(Partner::class);
    }

    /**
     * @return BelongsToMany<Odd>
     */
    public function odds(): BelongsToMany
    {
        return $this->belongsToMany(Odd::class);
    }

    /**
     * @return HasMany<Media>
     */
    public function medias(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    /**
     * Get the followups for the action.
     * @return HasMany<FollowUp>
     */
    public function followUps(): HasMany
    {
        return $this->hasMany(FollowUp::class);
    }

    /**
     * Get the followups for the action.
     * @return HasMany<History>
     */
    public function histories(): HasMany
    {
        return $this->hasMany(History::class);
    }

    #[Scope]
    public function departmentSelected(Builder $query): void
    {
        $query->where('department', UserRepository::departmentSelected());
    }

    #[Scope]
    public static function byState(Builder $query, string $state): void
    {
        $query->where('state', $state);
    }

}
