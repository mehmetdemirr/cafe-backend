<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NearbyRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true; // Yetkilendirme kontrolü yapılabilir.
    }

    public function rules(): array
    {
        return [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:1', // Varsayılan radius 5 olacağı için nullable yapılmıştır.
        ];
    }

    public function messages(): array
    {
        return [
            'latitude.required' => 'Enlem bilgisi gereklidir.',
            'latitude.numeric' => 'Enlem sayısal olmalıdır.',
            'latitude.between' => 'Enlem -90 ile 90 arasında olmalıdır.',
            'longitude.required' => 'Boylam bilgisi gereklidir.',
            'longitude.numeric' => 'Boylam sayısal olmalıdır.',
            'longitude.between' => 'Boylam -180 ile 180 arasında olmalıdır.',
            'radius.numeric' => 'Çap sayısal olmalıdır.',
            'radius.min' => 'Çap en az 1 kilometre olmalıdır.',
        ];
    }
}
