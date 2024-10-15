<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusinessEntryRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'business_id' => ['required', 'integer', 'exists:businesses,id'],
        ];
    }

    public function messages()
    {
        return [
            'business_id.required' => 'Business ID zorunludur.',
            'business_id.integer' => 'Business ID geçerli bir tamsayı olmalıdır.',
            'business_id.exists' => 'Geçersiz Business ID, böyle bir kafe mevcut değil.',
        ];
    }
}
