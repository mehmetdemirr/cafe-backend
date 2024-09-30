<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLoyaltyPointRequest extends BaseRequest
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
            'points' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'points.required' => 'The points field is required.',
            'points.integer' => 'The points must be an integer.',
            'points.min' => 'The points must be at least 1.',
        ];
    }
}
