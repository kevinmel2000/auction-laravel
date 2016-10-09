<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Response;

class UsersController extends Controller
{
    public function index()
    {
        return view('users.index');
    }

    public function table()
    {
        return Response::json(User::all());
    }
}
