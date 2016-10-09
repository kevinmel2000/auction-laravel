<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;
use Response;
use Hash;

class PasswordRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'oldPassword' => Auth::user()->password == Hash::make('123456') ? '' : 'required|password_check',
            'password' => 'required|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,15}$/|min:8|confirmed'
        ];
    }

    public function response(array $errors)
    {
        return Response::json(['status' => 'not_valid']);
    }
}
