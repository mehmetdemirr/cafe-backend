<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.exists' => 'The email address does not exist in our records.',
            'password.required' => 'Password is required.',
        ];
    }
}
