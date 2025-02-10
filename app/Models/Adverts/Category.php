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

    public function childrenRecursive()
    {
        return $this->hasMany(Category::class, 'parent_id')
            ->with('childrenRecursive')
            ->orderBy('_lft');
    }

}

