<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusinessEntryWithLocationRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'qr_code' => ['required', 'string', 'exists:businesses,qr_code'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ];
    }

    public function messages()
    {
        return [
            'qr_code.required' => 'QR kodu zorunludur.',
            'qr_code.string' => 'QR kodu geçerli bir metin olmalıdır.',
            'qr_code.exists' => 'Geçersiz QR kodu, böyle bir kafe mevcut değil.',
            'latitude.required' => 'Enlem (latitude) zorunludur.',
            'latitude.numeric' => 'Enlem (latitude) geçerli bir sayı olmalıdır.',
            'latitude.between' => 'Enlem (latitude) -90 ile 90 arasında olmalıdır.',
            'longitude.required' => 'Boylam (longitude) zorunludur.',
            'longitude.numeric' => 'Boylam (longitude) geçerli bir sayı olmalıdır.',
            'longitude.between' => 'Boylam (longitude) -180 ile 180 arasında olmalıdır.',
        ];
    }
}
