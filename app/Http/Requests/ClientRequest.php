<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            'firstName' => 'required|string|max:32|min:2|regex:/^[a-zA-Z]+$/',
            'lastName' => 'required|string|max:32|min:2|regex:/^[a-zA-Z]+$/',
            'email' => 'required|string',
            'phoneNumber' => 'required|regex:/^\+?[1-9]\d{1,14}$/',
        ];
    }
}
