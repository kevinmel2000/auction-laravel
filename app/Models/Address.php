<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'address';
    protected $fillable = [
        'user_id', 'name', 'post', 'country_id', 'city', 'address', 'phone', 'details'
    ];
}
