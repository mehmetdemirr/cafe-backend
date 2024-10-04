<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileDetailRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:15',
            'biography' => 'nullable|string|max:500',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'gender' => 'nullable|string|in:male,female,other', 
            'profile_picture' => 'nullable|file|mimes:jpg,jpeg,png|max:4096',
        ];
    }

    public function messages()
    {
        return [
            'name.string' => 'İsim geçersiz.',
            'name.max' => 'İsim en fazla 255 karakter olmalıdır.',
            'phone_number.string' => 'Telefon numarası geçersiz.',
            'phone_number.max' => 'Telefon numarası en fazla 15 karakter olmalıdır.',
            'biography.string' => 'Biyografi geçersiz.',
            'biography.max' => 'Biyografi en fazla 500 karakter olmalıdır.',
            'country.string' => 'Ülke adı geçersiz.',
            'country.max' => 'Ülke adı en fazla 100 karakter olmalıdır.',
            'city.string' => 'Şehir adı geçersiz.',
            'city.max' => 'Şehir adı en fazla 100 karakter olmalıdır.',
            'district.string' => 'Semt adı geçersiz.',
            'district.max' => 'Semt adı en fazla 100 karakter olmalıdır.',
            'gender.string' => 'Cinsiyet geçersiz.',
            'gender.in' => 'Cinsiyet, sadece male, female veya other değerlerinden biri olmalıdır.',
            'profile_picture.file' => 'Profil fotoğrafı geçersiz.',
            'profile_picture.mimes' => 'Profil fotoğrafı sadece jpg, jpeg veya png formatında olmalıdır.',
            'profile_picture.max' => 'Profil fotoğrafı en fazla 4 MB boyutunda olmalıdır.',
        ];
    }
}
