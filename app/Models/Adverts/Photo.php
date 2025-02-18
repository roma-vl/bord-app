<?php

namespace App\Models\Adverts;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'advert_advert_photos';

    public $timestamps = false;

    protected $fillable = ['file', 'advert_id'];
}
