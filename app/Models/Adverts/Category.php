<?php

namespace App\Models\Adverts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use NodeTrait;

    protected $table = 'advert_categories';
    public $timestamps = false;
    protected $fillable = ['name', 'slug', 'parent_id'];


    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('children');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (empty($model->slug)) {
                $slug = Str::slug($model->name, '-');

                $count = self::where('slug', 'LIKE', $slug . '%')->count();
                if ($count > 0) {
                    $slug .= '-' . ($count + 1);
                }

                $model->slug = $slug;
            }
        });
    }
}

