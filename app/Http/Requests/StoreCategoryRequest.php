<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // image_url alanı olarak güncellendi
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/', // Renk kodu formatı
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Kategori adı zorunludur.',
            'name.string' => 'Kategori adı bir metin olmalıdır.',
            'name.max' => 'Kategori adı en fazla 255 karakter olabilir.',
            'image.image' => 'Dosya bir resim olmalıdır.',
            'image.mimes' => 'Resim yalnızca jpeg, png, jpg veya gif formatında olmalıdır.',
            'image.max' => 'Resim en fazla 2 MB olabilir.',
            'color.regex' => 'Geçerli bir renk kodu giriniz. (Örnek: #FFFFFF)',
        ];
    }
}
