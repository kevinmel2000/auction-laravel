<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Session;
use Redis;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'firstname', 'lastname', 'gender', 'age', 'admin', 'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function address()
    {
        return $this->hasOne('App\Models\Address');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'product_user', 'user_id', 'product_id');
    }

    public function addNodeUser()
    {
        $user = Auth::user();

        if($user->node_token == '') {
            $user->node_token = md5(date('Y-m-d H:i:s') . uniqid());
            $user->connections = 1;
        } else{
            $user->connections++;
        }

        $user->save();

        Redis::publish(
            'laravel',
            json_encode([
                'action' => 'addUser',
                'data' => [
                    'id' => $user->id,
                    'token' => $user->node_token
                ]
            ])
        );

        Session::put('node', $user->node_token);
        Session::put('device', md5(date('Y-m-d H:i:s') . uniqid()));

        return true;
    }

    public function deleteNodeUser()
    {
        $user = Auth::user();

        Redis::publish(
            'laravel',
            json_encode([
                'action' => 'deleteUser',
                'data' => [
                    'id' => $user->id,
                    'token' => $user->node_token,
                    'device' => Session::get('device')
                ]
            ])
        );

        Session::forget('node');
        Session::forget('device');

        if($user->connections > 1) {
            $user->connections--;
        } else {
            $user->connections = 0;
            $user->node_token = '';
        }

        $user->save();
    }

    public function getUsers() {
        $users = $this->where('node_token', '!=', '')
            ->select('id', 'node_token AS token', 'name')
            ->get();

        foreach($users as &$user) {
            $user->connections = [];
        }

        return $users;
    }
}
