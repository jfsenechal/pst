<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser, HasAvatar, HasName
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
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
        ];
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

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            //&& $this->hasVerifiedEmail()
            return $this->hasRole(Role::ROLE_ADMIN);
        }

        if ($panel->getId() === 'front') {
            return $this->hasRole(Role::ROLE_AGENT);
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
        return $this->avatar_url;//Filament will fall back to ui-avatars.com.
    }
}
