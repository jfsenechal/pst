<?php

namespace App\Models;

use App\Constant\ActionStateEnum;
use App\Constant\ActionTypeEnum;
use App\Models\Scopes\DepartmentScope;
use App\Observers\ActionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

//#[ScopedBy([DepartmentScope::class])]
#[ObservedBy([ActionObserver::class])]
class Action extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'state',
        'state_percentage',
        'type',
        'odd_roadmap',
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
                $model->department = $user->departments[0];
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
    public function department(Builder $query, string $department): void
    {
        $query->where('department', $department);
    }

    #[Scope]
    public function allDepartment(Builder $builder): void
    {
        $user = auth()->user();
        $departments = $user->departments ?? [];
        if (count($departments) > 0) {
            $department = $departments[0];
        }
        $builder->where('department', $department);
    }

    #[Scope]
    public static function byState(Builder $query, string $state): void
    {
        $query->where('state', $state);
    }

}
