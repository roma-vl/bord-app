<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LocatedVillage extends Model
{
    use HasFactory;

    protected $table = 'located_village';

    protected $fillable = ['country', 'region', 'area', 'village', 'slug'];

    /**
     * Автоматична генерація slug перед збереженням.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (empty($model->slug)) {
                $slug = Str::slug($model->village, '-');

                // Якщо такий slug вже є, додаємо унікальний ідентифікатор
                $count = self::where('slug', 'LIKE', $slug . '%')->count();
                if ($count) {
                    $slug .= '-' . ($count + 1);
                }

                $model->slug = $slug;
            }
        });
    }

    /**
     * Зв'язок з країною.
     */
    public function country()
    {
        return $this->belongsTo(LocatedCountry::class, 'country');
    }

    /**
     * Зв'язок з регіоном.
     */
    public function region()
    {
        return $this->belongsTo(LocatedRegion::class, 'region');
    }

    /**
     * Зв'язок з районом.
     */
    public function area()
    {
        return $this->belongsTo(LocatedArea::class, 'area');
    }
}
