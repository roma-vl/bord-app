<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocatedCountry extends Model
{
    use HasFactory;

    protected $table = 'located_countrys';
    protected $fillable = ['country', 'slug'];

    public function regions()
    {
        return $this->hasMany(LocatedRegion::class, 'country');
    }
}
