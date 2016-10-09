<?php

namespace App\Models\Mongo;

use Jenssegers\Mongodb\Eloquent\Model;

class Bid extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'bids';
    protected $fillable = [
        'product_id', 'type', 'name', 'price', 'user_id', 'date'
    ];
}
