<?php

namespace App\Http\Requests;

use Response;

class ProductRequest extends Request
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
            'template_id' => 'required',
            'category_id' => 'required',
            'start_date' => 'required',
            'type' => 'required|in:beginning,ticket,common',
            'source' => 'required|in:game_zone,common',
            'price' => 'required',
            'coefficient' => 'required',
            'bot_count' => 'required',
            'bot_names' => 'required'
        ];
    }

    public function response(array $errors)
    {
        return Response::json(['status' => 'not_valid']);
    }
}
