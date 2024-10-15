<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusinessCreateRequest extends BaseRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'İşletme adı zorunludur.',
            'address.required' => 'Adres zorunludur.',
        ];
    }
}
