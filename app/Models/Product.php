<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $table = 'products';
    protected $fillable = [
        'mongo_id', 'template_id', 'category_id', 'bot_count', 'bot_names',
        'price', 'start_date', 'type', 'coefficient', 'source', 'status', 'registered_users'
    ];

    public function template() 
    {
        return $this->belongsTo('App\Models\Template');
    }

    public function category() 
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\User', 'product_user', 'product_id', 'user_id');
    }
}
