<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Kalnoy\Nestedset\NodeTrait;

class Location extends Model
{
    use HasFactory,NodeTrait;

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

    protected static function booted(): void
    {
        static::creating(function (Location $location) {
            if ($location->parent_id) {
                $parent = Location::find($location->parent_id);
                if ($parent) {
                    $location->depth = $parent->depth + 1;
                }
            } else {
                $location->depth = 0;
            }
        });
    }

}
