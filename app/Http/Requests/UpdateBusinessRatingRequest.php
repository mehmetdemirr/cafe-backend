<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBusinessRatingRequest extends BaseRequest
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
            'rating' => 'required|integer|between:1,5', // 1 ile 5 arasında bir puan
            'comment' => 'nullable|string|max:255', // Yorum isteğe bağlı ve 255 karakterden uzun olamaz
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => 'Değerlendirme puanı gereklidir.',
            'rating.integer' => 'Değerlendirme puanı bir tamsayı olmalıdır.',
            'rating.between' => 'Değerlendirme puanı 1 ile 5 arasında olmalıdır.',
            'comment.string' => 'Yorum bir metin olmalıdır.',
            'comment.max' => 'Yorum 255 karakterden uzun olamaz.',
        ];
    }
}
