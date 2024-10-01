<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupportMessageRequest extends BaseRequest
{

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        return [
            'message' => 'required|string|max:1000',
        ];
    }

    // Özelleştirilmiş hata mesajları
    public function messages(): array
    {
        return [
            'message.required' => 'Destek mesajı zorunludur.',
            'message.string' => 'Mesaj metin formatında olmalıdır.',
            'message.max' => 'Mesaj en fazla 1000 karakter olabilir.',
        ];
    }
}
