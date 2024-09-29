<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuCategoryRequest extends BaseRequest
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
            'name' => 'required|string|max:255',
            'business_id' => 'required|exists:businesses,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Kategori adı gereklidir.',
            'business_id.required' => 'İşletme ID gereklidir.',
            'business_id.exists' => 'İşletme bulunamadı.',
        ];
    }
}
