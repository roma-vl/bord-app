<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Page extends Model
{
    use HasFactory, NodeTrait;
    protected $table = 'pages';
    protected $guarded = [];

    public function getPath(): string
    {
        return implode('/', array_merge(
            $this->ancestors()->defaultOrder()->pluck('slug')->toArray(),
            [$this->slug]
        ));
    }

    public function getMenuTitle(): string
    {
        return $this->menu_title ?: $this->title;
    }

    public function childrenRecursive()
    {
        return $this->hasMany(self::class, 'parent_id')
            ->with('childrenRecursive')
            ->orderBy('_lft');
    }
}
