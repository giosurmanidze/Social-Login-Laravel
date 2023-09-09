<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialLoginRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email|unique:users,email',
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => "Couldn't log in. Maybe you used a different login method?",
        ];
    }
}
