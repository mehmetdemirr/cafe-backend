<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComplaintRequest extends BaseRequest
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
            'reported_user_id' => 'required|exists:users,id',
            'reason' => 'required|string|max:255',
            'details' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'reported_user_id.required' => 'Şikayet edilen kullanıcı belirtilmelidir.',
            'reported_user_id.exists' => 'Şikayet edilen kullanıcı bulunamadı.',
            'reason.required' => 'Şikayet sebebi belirtilmelidir.',
            'reason.string' => 'Şikayet sebebi geçerli bir metin olmalıdır.',
            'reason.max' => 'Şikayet sebebi 255 karakteri geçemez.',
        ];
    }
}
