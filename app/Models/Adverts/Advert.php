<?php

namespace App\Models\Adverts;

use App\Models\LocatedArea;
use App\Models\LocatedCountry;
use App\Models\LocatedRegion;
use App\Models\LocatedVillage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advert extends Model
{
    use HasFactory, SoftDeletes;

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

    public function country()
    {
        return $this->belongsTo(LocatedCountry::class, 'country_id');
    }
    public function region()
    {
        return $this->belongsTo(LocatedRegion::class, 'region_id');
    }

    public function area()
    {
        return $this->belongsTo(LocatedArea::class, 'area_id');
    }

    public function village()
    {
        return $this->belongsTo(LocatedVillage::class, 'village_id');
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
}
