<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventCreateRequest extends BaseRequest
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
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'business_id' => 'required|exists:businesses,id', // İşletme ID'si geçerli olmalı
            'quota' => 'nullable|integer',
            'is_paid' => 'required|boolean',
            'category' => 'required|integer|between:1,4', // 1-4 arasında değer
            'image_url' => 'nullable|string',
            'location' => 'nullable|string',
            'price' => 'nullable|numeric',
            'registration_deadline' => 'nullable|date',
            'is_offsite' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'start_date.required' => 'Başlangıç tarihi gereklidir.',
            'start_date.date' => 'Başlangıç tarihi geçerli bir tarih olmalıdır.',
            'end_date.date' => 'Bitiş tarihi geçerli bir tarih olmalıdır.',
            'end_date.after' => 'Bitiş tarihi, başlangıç tarihinden sonra olmalıdır.',
            'business_id.required' => 'İşletme ID’si gereklidir.',
            'business_id.exists' => 'Geçersiz işletme ID’si.',
            'is_paid.required' => 'Ücretli durumu gereklidir.',
            'is_paid.boolean' => 'Ücretli durumu geçerli bir boolean olmalıdır.',
            'category.required' => 'Kategori gereklidir.',
            'category.integer' => 'Kategori değeri bir tamsayı olmalıdır.',
            'category.between' => 'Kategori değeri 1 ile 4 arasında olmalıdır.',
            'image_url.string' => 'Görsel URL geçerli bir metin olmalıdır.',
            'price.numeric' => 'Ücret geçerli bir sayı olmalıdır.',
            'registration_deadline.date' => 'Kayıt son tarihi geçerli bir tarih olmalıdır.',
            'is_offsite.required' => 'İşletme dışı durumu gereklidir.',
            'is_offsite.boolean' => 'İşletme dışı durumu geçerli bir boolean olmalıdır.',
        ];
    }
}
