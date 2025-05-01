<?php

namespace App\Models;

use App\Models\Adverts\Advert;
use App\Notifications\CustomVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
/**
 * @property $phone_verified
 * @property $phone_verify_token
 * @property $phone_verify_token_expire
 */
class User extends Authenticatable implements MustVerifyEmail, Auditable
{
    use HasFactory, Notifiable, SoftDeletes, AuditableTrait;

    protected $fillable = [
        'first_name',
        'name',
        'last_name',
        'email',
        'password',
        'locale',
        'email_verified_at',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public const array LOCALES = ['en', 'uk'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'phone_verify_token_expire' => 'datetime',
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
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permissionKey) {
            $query->where('key', $permissionKey);
        })->exists();
    }
    public function getPermissions(): array
    {
        return $this->roles()
            ->with('permissions:key,id')
            ->get()
            ->flatMap(fn($role) => $role->permissions->pluck('key'))
            ->unique()
            ->values()
            ->toArray();
    }

    public function unverifyPhone(): void
    {
        $this->phone_verified = false;
        $this->phone_verify_token = null;
        $this->phone_verify_token_expire = null;
        $this->saveOrFail();
    }

    public function requestPhoneVerification(Carbon $now): string
    {
        if (empty($this->phone)) {
            throw new \DomainException('Phone number is empty.');
        }
        if (!empty($this->phone_verify_token) && $this->phone_verify_token_expire &&
            $this->phone_verify_token_expire->gt($now)) {
            throw new \DomainException('Phone is already requested.');
        }
        $this->phone_verified = false;
        $this->phone_verify_token = (string)random_int(10000, 99999);
        $this->phone_verify_token_expire = $now->copy()->addSeconds(300);
        $this->saveOrFail();

        return $this->phone_verify_token;
    }

    public function verifyPhone($token, Carbon $now): void
    {
        if ($token !== $this->phone_verify_token) {
            throw new \DomainException('Incorrect verify token.');
        }
        if ($this->phone_verify_token_expire->lt($now)) {
            throw new \DomainException('Token is expired.');
        }
        $this->phone_verified = true;
        $this->phone_verify_token = null;
        $this->phone_verify_token_expire = null;
        $this->saveOrFail();
    }

    public function addToFavorites($id): void
    {
        if ($this->hasIsFavorites($id)) {
            throw new \DomainException('This advert is already in favorites.');
        }
        $this->favorites()->attach($id);
    }

    public function removeFromFavorites($id): void
    {
        if (!$this->hasIsFavorites($id)) {
            throw new \DomainException('This advert is not in favorites.');
        }
        $this->favorites()->detach($id);
    }

    public function hasIsFavorites($id): bool
    {
        return $this->favorites()->where('id', $id)->exists();
    }
    public function favorites()
    {
        return $this->belongsToMany(User::class, 'advert_advert_favorites', 'user_id','advert_id');
    }
}
