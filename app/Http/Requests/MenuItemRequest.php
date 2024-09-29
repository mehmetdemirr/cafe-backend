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
            'business_id' => 'required|exists:businesses,id',
            'views' => 'nullable|integer',
            'is_available' => 'nullable|boolean',
            'additional_info' => 'nullable|string',
            'calories' => 'nullable|integer',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Menu item name is required.',
            'price.required' => 'Price is required.',
            'menu_category_id.required' => 'Menu category ID is required.',
            'business_id.required' => 'Business ID is required.',
            'menu_category_id.exists' => 'The selected menu category does not exist.',
            'business_id.exists' => 'The selected business does not exist.',
        ];
    }
}