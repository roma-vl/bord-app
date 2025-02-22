<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Kalnoy\Nestedset\NodeTrait;

class Location extends Model
{
    use NodeTrait;

    protected $fillable = ['name', 'slug', 'depth', 'parent_id'];

    public static function generateSlug($name, $parentId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (self::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '_' . $counter;
            $counter++;
        }

        return $slug;
    }
}
