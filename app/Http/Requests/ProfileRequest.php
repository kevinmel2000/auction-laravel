<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Response;

class ProfileRequest extends Request
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
            'name' => 'required|max:16'
        ];
    }

    public function response(array $errors)
    {
        return Response::json(['status' => 'not_valid']);
    }
}
