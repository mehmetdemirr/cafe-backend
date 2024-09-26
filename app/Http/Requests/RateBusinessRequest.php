<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RateBusinessRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'rating' => 'required|integer|between:1,5', // 1 ile 5 arasında bir tamsayı
            'comment' => 'nullable|string|max:255', // 255 karaktere kadar isteğe bağlı bir metin
        ];
    }

    public function messages()
    {
        return [
            'rating.required' => 'Puanlama zorunludur.',
            'rating.integer' => 'Puanlama bir tam sayı olmalıdır.',
            'rating.between' => 'Puanlama 1 ile 5 arasında olmalıdır.',
            'comment.string' => 'Yorum bir metin olmalıdır.',
            'comment.max' => 'Yorum en fazla 255 karakter olmalıdır.',
        ];
    }
}
