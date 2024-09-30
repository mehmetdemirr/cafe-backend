<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuItemImageRequest extends  BaseRequest
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
            'menu_item_id' => 'required|exists:menu_items,id',
            'image_url' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Maksimum 2MB
        ];
    }

    public function messages()
    {
        return [
            'menu_item_id.required' => 'Menü öğesi ID\'si gereklidir.',
            'menu_item_id.exists' => 'Geçersiz menü öğesi ID\'si.',
            'image_url.required' => 'Görsel dosyası gereklidir.',
            'image_url.image' => 'Yüklenen dosya bir görsel olmalıdır.',
            'image_url.mimes' => 'Görsel yalnızca jpg, jpeg veya png formatında olmalıdır.',
            'image_url.max' => 'Görsel boyutu 2MB\'ı aşmamalıdır.',
        ];
    }
}
