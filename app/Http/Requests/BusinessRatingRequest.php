<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusinessRatingRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rating' => 'required|integer|between:1,5', // 1 ile 5 arasında bir puan
            'comment' => 'nullable|string|max:255', // Yorum isteğe bağlı ve 255 karakterden uzun olamaz
            'user_id' => 'required|exists:users,id', // Kullanıcı kimliği zorunlu ve mevcut olmalı
            'business_id' => 'required|exists:businesses,id', // İşletme kimliği zorunlu ve mevcut olmalı
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
            'user_id.required' => 'Kullanıcı kimliği gereklidir.',
            'user_id.exists' => 'Verilen kullanıcı kimliği mevcut değildir.',
            'business_id.required' => 'İşletme kimliği gereklidir.',
            'business_id.exists' => 'Verilen işletme kimliği mevcut değildir.',
        ];
    }
}
