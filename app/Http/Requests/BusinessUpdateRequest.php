<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusinessUpdateRequest extends BaseRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:255',
            // 'qr_code' => 'nullable|string|max:255',
            'phone_number' => 'sometimes|nullable|string|max:15', // Telefon numarası için kural
            'website_url' => 'sometimes|nullable|url|max:255', // Web sitesi adresi için kural
            'description' => 'sometimes|nullable|string|max:500', // İşletme açıklaması için kural
            'location_latitude' => 'sometimes|nullable|numeric', // Enlem için kural
            'location_longitude' => 'sometimes|nullable|numeric', // Boylam için kural
            'image_url' => 'sometimes|nullable|string|max:255', // İşletme resmi için kural
            'opening_time' => 'sometimes|nullable|string|max:10', // Açılış saati için kural
            'closing_time' => 'sometimes|nullable|string|max:10', // Kapanış saati için kural
            'slug' => 'sometimes|required|string|max:255|unique:businesses,slug,' .$this->user()->business->id, // Slug için kural
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'İşletme adı zorunludur.',
            'address.required' => 'Adres zorunludur.',
            'phone_number.max' => 'Telefon numarası en fazla 15 karakter olmalıdır.',
            'website_url.url' => 'Web sitesi adresi geçerli bir URL olmalıdır.',
            'description.max' => 'Açıklama en fazla 500 karakter olmalıdır.',
            'location_latitude.numeric' => 'Enlem geçerli bir sayı olmalıdır.',
            'location_longitude.numeric' => 'Boylam geçerli bir sayı olmalıdır.',
            'opening_time.max' => 'Açılış saati en fazla 10 karakter olmalıdır.',
            'closing_time.max' => 'Kapanış saati en fazla 10 karakter olmalıdır.',
            'slug.required' => 'Slug zorunludur.',
            'slug.unique' => 'Bu slug zaten mevcut.',
        ];
    }
}
