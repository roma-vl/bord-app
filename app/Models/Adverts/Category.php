<?php

namespace App\Models\Adverts;

use Illuminate\Database\Eloquent\Model;
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

    public function getParentAttributes()
    {
        $parents = $this->ancestors()->with('attributes')->get(); // Отримуємо всіх батьків

        return $parents->map(function ($parent) {
            return $parent->attributes()->orderBy('sort')->get(); // Отримуємо атрибути кожного батька
        })->flatten(); // Вирівнюємо масив
    }



    public function getAllAttributes()
    {
        return $this->getParentAttributes()->merge($this->attributes);
    }

    public function allAttributes(): array
    {
        $parent = $this->getParentAttributes();
        $attr = $this->attributes()->orderBy('sort')->getModels();
        return array_merge($parent, $attr);
    }
    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'category_id', 'id');
    }

    public function childrenRecursive()
    {
        return $this->hasMany(Category::class, 'parent_id')
            ->with('childrenRecursive')
            ->orderBy('_lft');
    }

}

