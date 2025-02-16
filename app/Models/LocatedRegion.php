<?php

namespace App\Models;

use App\Models\Adverts\Advert;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocatedRegion extends Model
{
    use HasFactory;

    protected $table = 'located_region';
    protected $fillable = ['country', 'region', 'slug'];

    public function country()
    {
        return $this->belongsTo(LocatedCountry::class, 'country');
    }

    public function areas()
    {
        return $this->hasMany(LocatedArea::class, 'region');
    }

    public function adverts()
    {
        return $this->hasMany(Advert::class, 'region_id');
    }

}
