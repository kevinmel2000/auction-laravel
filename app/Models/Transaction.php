<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public $table = 'transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'user_id', 'product_id', 'total_amount', 'status', 'type', 'note'
    ];

    /**
     * Get ID of last row from table
     *
     * @return int
     */
    public static function getLastTransactionID(){
        $model = self::orderBy('id', 'desc')->first();
        return $model ? $model->id : 0;
    }
}
