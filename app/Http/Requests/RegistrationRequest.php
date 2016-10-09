<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Response;

class RegistrationRequest extends Request
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
            'email' => 'required|email|unique:users',
            'password' => 'required|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,15}$/|min:8',
            'name' => 'required|min:4|unique:users',
            'toa' => 'required',
            'pda' => 'required'
        ];
    }
    
    public function response(array $errors)
    {
        return Response::json(['status' => 'not_valid']);
    }
}
