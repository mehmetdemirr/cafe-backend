<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MatchRequest extends BaseRequest
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
            'swiped_user_id' => 'required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'swiped_user_id.required' => 'Swiped user ID alanı zorunludur.',
            'swiped_user_id.exists' => 'Seçilen kullanıcı mevcut değil.',
        ];
    }
}
