<?php

namespace App\Models\Mongo;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'users';

    protected $fillable = [
        'name', 'email', 'password', 'firstname', 'lastname', 'gender', 'age', 'admin', 'avatar', 'bets'
    ];
}
