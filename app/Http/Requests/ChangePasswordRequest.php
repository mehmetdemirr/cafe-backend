<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed', // yeni parola ve yeni parolanın tekrarını kontrol et
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Mevcut parola gereklidir.',
            'new_password.required' => 'Yeni parola gereklidir.',
            'new_password.min' => 'Yeni parola en az 8 karakter olmalıdır.',
            'new_password.confirmed' => 'Yeni parolalar eşleşmiyor.',
        ];
    }
}
