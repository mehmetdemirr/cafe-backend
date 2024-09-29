<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CampaignRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'business_id' => 'required|exists:businesses,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Kampanya başlığı gereklidir.',
            'title.string' => 'Kampanya başlığı bir metin olmalıdır.',
            'title.max' => 'Kampanya başlığı en fazla 255 karakter olabilir.',
            'description.string' => 'Açıklama bir metin olmalıdır.',
            'start_date.required' => 'Başlangıç tarihi gereklidir.',
            'start_date.date' => 'Başlangıç tarihi geçerli bir tarih olmalıdır.',
            'start_date.after_or_equal' => 'Başlangıç tarihi bugünden sonraki bir tarih olmalıdır.',
            'end_date.required' => 'Bitiş tarihi gereklidir.',
            'end_date.date' => 'Bitiş tarihi geçerli bir tarih olmalıdır.',
            'end_date.after' => 'Bitiş tarihi, başlangıç tarihinden sonra olmalıdır.',
            'business_id.required' => 'İşletme ID’si gereklidir.',
            'business_id.exists' => 'Belirtilen işletme mevcut değildir.',
        ];
    }
}
