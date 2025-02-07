<?php

namespace App\Models;

use App\Notifications\CustomVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'locale',
        'email_verified_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public const array LOCALES = ['en', 'uk'];

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
        ];
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new CustomVerifyEmail());
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function hasPermission($permissionKey)
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($permissionKey) {
            $query->where('key', $permissionKey);
        })->exists();
    }

    public function getPermissions(): array
    {
        return $this->role->permissions->pluck('id')->toArray();
    }

}
