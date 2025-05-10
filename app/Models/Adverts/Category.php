<?php

namespace App\Models\Adverts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use HasFactory, NodeTrait;

    protected $table = 'advert_categories';
    public $timestamps = false;
    protected $fillable = ['name', 'slug', 'parent_id'];


    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('children');
    }

    public function rootWithOneChildren(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')
            ->select(['id', 'name', 'parent_id', 'slug']);
    }


    public function getParentAttributes()
    {
        $parents = $this->ancestors()->with('attributes')->get();

        return $parents->map(function ($parent) {
            return $parent->attributes()->orderBy('sort')->get();
        })->flatten();
    }

    public function allArrayAttributes(): array
    {
        $parent = $this->getParentAttributes()->toArray();
        $attr = $this->attributes()->orderBy('sort')->get()->toArray();
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

