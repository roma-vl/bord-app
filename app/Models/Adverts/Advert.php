<?php

namespace App\Models\Adverts;

use App\Models\Location;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Advert extends Model implements Auditable
{
    use HasFactory, SoftDeletes, AuditableTrait;

    public const string STATUS_DRAFT = 'draft';
    public const string STATUS_MODERATION = 'moderation';
    public const string STATUS_ACTIVE = 'active';
    public const string STATUS_CLOSED = 'closed';
    public const string STATUS_MODERATION_FAIL = 'moderation_fail';
    public const string STATUS_BANNED = 'banned';
    public const string STATUS_DELETED = 'deleted';
    public const string STATUS_EXPIRED = 'expired';
    protected $table = 'advert_adverts';
    protected $guarded = ['id'];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at'   => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function value()
    {
        return $this->hasMany(Value::class, 'advert_id');
    }

    public function photo()
    {
        return $this->hasMany(Photo::class, 'advert_id', 'id');
    }
    public function region()
    {
        return $this->belongsTo(Location::class, 'region_id');
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function sendToModeration(): void
    {
        if (!$this->isDraft()) {
            throw new \DomainException('Advert is not draft.');
        }
//        if (!\count($this->photo)) {
//            throw new \DomainException('Upload photos.');
//        }
        $this->update([
            'status' => self::STATUS_MODERATION,
        ]);
    }

    public function moderate(Carbon $date): void
    {
        if ($this->status !== self::STATUS_MODERATION) {
            throw new \DomainException('Advert is not sent to moderation.');
        }
        $this->update([
            'published_at' => $date,
            'expires_at' => $date->copy()->addDays(15),
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public function reject($reason): void
    {
        $this->update([
            'status' => self::STATUS_DRAFT,
            'reject_reason' => $reason,
        ]);
    }
    public function backToDraft(): void
    {
        $this->update([
            'status' => self::STATUS_DRAFT,
        ]);
    }
    public function expire(): void
    {
        $this->update([
            'status' => self::STATUS_CLOSED,
        ]);
    }
    public function active(): void
    {
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'reject_reason' => '',
        ]);
    }

    public function close(): void
    {
        $this->update([
            'status' => self::STATUS_CLOSED,
        ]);
    }

    public static function statusesList(): array
    {
        return [
            self::STATUS_DRAFT           => 'Чернетка',
            self::STATUS_MODERATION      => 'На модерації',
            self::STATUS_ACTIVE          => 'Активний',
            self::STATUS_CLOSED          => 'Закритий',
            self::STATUS_MODERATION_FAIL => 'Не пройшов модерацію',
            self::STATUS_BANNED          => 'Заблокований',
            self::STATUS_DELETED         => 'Видалений',
            self::STATUS_EXPIRED         => 'Закінчився термін дії',
        ];
    }
    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

    public function scopeForCategory(Builder $query, Category $category)
    {
        return $query->whereIn('category_id', array_merge(
            [$category->id],
            $category->descendants()->pluck('id')->toArray()
        ));
    }
}
