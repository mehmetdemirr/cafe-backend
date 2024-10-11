<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuItemRequest extends BaseRequest
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
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'menu_category_id' => 'required|exists:menu_categories,id',
            'is_available' => 'nullable|boolean',
            'additional_info' => 'nullable|string',
            'calories' => 'nullable|integer',
            'image_url' => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Menu item name is required.',
            'price.required' => 'Price is required.',
            'menu_category_id.required' => 'Menu category ID is required.',
            'menu_category_id.exists' => 'The selected menu category does not exist.',
            'image_url.image' => 'Dosya bir resim olmalıdır.',
            'image_url.mimes' => 'Resim dosyası yalnızca jpeg, png, jpg veya gif formatında olmalıdır.',
            'image_url.max' => 'Resim dosyası maksimum 2MB olabilir.',
        ];
    }
}