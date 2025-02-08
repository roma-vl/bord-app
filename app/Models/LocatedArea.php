<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LocatedArea extends Model
{
    use HasFactory;

    protected $table = 'located_area';

    protected $fillable = ['country', 'region', 'area', 'slug'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (empty($model->slug)) {
                $slug = Str::slug($model->area, '-');

                // Якщо такий slug вже є, додаємо ID
                if (self::where('slug', $slug)->exists()) {
                    $slug .= '-' . $model->id;
                }

                $model->slug = $slug;
            }
        });
    }

    public function country()
    {
        return $this->belongsTo(LocatedCountry::class, 'country');
    }

    public function region()
    {
        return $this->belongsTo(LocatedRegion::class, 'region');
    }

    public function villages()
    {
        return $this->hasMany(LocatedVillage::class, 'area');
    }

}

