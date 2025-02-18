<?php

namespace App\Models\Adverts;

use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    protected $table = 'advert_advert_values';

    public $timestamps = false;

    protected $fillable = ['attribute_id', 'value'];
}
