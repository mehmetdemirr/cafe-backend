<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventActiveRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category' => 'nullable|integer|between:1,4', // Kategori 1-4 arasında olmalı (isteğe bağlı)
            'per_page' => 'nullable|integer|min:1', // Sayfa başına en az 1 etkinlik
            'page' => 'nullable|integer|min:1', // Sayfa numarası (isteğe bağlı)
        ];
    }

    public function messages(): array
    {
        return [
            'category.integer' => 'Kategori, bir tamsayı olmalıdır.',
            'category.between' => 'Kategori, 1 ile 4 arasında olmalıdır.',
            'per_page.integer' => 'Sayfa başına gösterilecek etkinlik sayısı, bir tamsayı olmalıdır.',
            'per_page.min' => 'Sayfa başına gösterilecek etkinlik sayısı en az 1 olmalıdır.',
            'page.integer' => 'Sayfa numarası, bir tamsayı olmalıdır.',
            'page.min' => 'Sayfa numarası en az 1 olmalıdır.',
        ];
    }
}
