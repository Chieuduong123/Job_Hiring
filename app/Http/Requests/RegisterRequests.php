<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequests extends FormRequest
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
            'email' =>'required|email',
            'password' => 'required|min:6',
            'phone' => 'required|numeric|digits:10'
        ];
    }

    /**
     * Determine message.
     *
     * @return array
     */
    public function messages()
    {
        return [
	
        ];
    }
}