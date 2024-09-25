<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends BaseRequest
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
    public function rules(): array
    {
        // Giriş yapan kullanıcının ID'sini alıyoruz
        $userId = $this->user()->id;
        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $userId,
            'password' => 'sometimes|string|min:6|confirmed',
        ];
    }


    public function messages(): array
    {
        return [
            'name.sometimes' => 'İsim zorunlu değildir ama eğer verildiyse bir string olmalıdır.',
            'name.string' => 'İsim bir string olmalıdır.',
            'name.max' => 'İsim en fazla 255 karakter uzunluğunda olabilir.',
            'email.sometimes' => 'Email zorunlu değildir ama eğer verildiyse geçerli bir email adresi olmalıdır.',
            'email.string' => 'Email bir string olmalıdır.',
            'email.email' => 'Geçerli bir email adresi girin.',
            'email.unique' => 'Bu email adresi zaten kullanılıyor.',
            'email.max' => 'Email en fazla 255 karakter uzunluğunda olabilir.',
            'password.sometimes' => 'Şifre zorunlu değildir ama eğer verildiyse minimum 6 karakter uzunluğunda olmalıdır.',
            'password.string' => 'Şifre bir string olmalıdır.',
            'password.min' => 'Şifre en az 6 karakter uzunluğunda olmalıdır.',
            'password.confirmed' => 'Şifre doğrulaması eşleşmiyor.',
        ];
    }
}
