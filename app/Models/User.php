<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Constant\RoleEnum;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;

class User extends Authenticatable implements FilamentUser, HasAvatar, HasName
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'phone',
        'extension',
        'mobile',
        'username',
        'departments',
        'uuid',
        'mandatory',
        'color_primary',
        'color_secondary',
        'email',
        'password',
        'plainPassword',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function ($model) {
            // Unset the field so it doesn't save to the database
            if (isset($model->attributes['plainPassword'])) {
                $model->plainPassword = $model->attributes['plainPassword'];
                unset($model->attributes['plainPassword']);
            }
        });
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'roles' => 'array',
            'departments' => 'array',
        ];
    }

    public function name(): string
    {
        return $this->last_name.' '.$this->first_name;
    }

    /**
     * The roles that belong to the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->BelongsToMany(Role::class);
    }

    public function hasRole(string $roleToFind): bool
    {
        foreach ($this->roles()->get() as $role) {
            if ($role->name === $roleToFind) {
                return true;
            }
        }

        return false;
    }

    public function hasRoles(array $rolesToFind): bool
    {
        foreach ($rolesToFind as $roleToFind) {
            foreach ($this->roles()->get() as $role) {
                if ($role->name === $roleToFind) {
                    return true;
                }
            }
        }

        return false;
    }

    public function addRole(Role $role): void
    {
        $this->roles()->attach($role);
    }

    /**
     * @return BelongsToMany<Service>
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }

    /**
     * @return BelongsToMany<Action>
     */
    public function actions(): BelongsToMany
    {
        return $this->belongsToMany(Action::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return ($this->hasRole(RoleEnum::ADMIN->value) || $this->hasRole(RoleEnum::AGENT->value));
        }

        return false;
    }

    /**
     * hasName
     */
    public function getFilamentName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return null;
    }
}
