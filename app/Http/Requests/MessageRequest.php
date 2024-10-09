<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends BaseRequest
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
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string|max:1000',
            'media_path' => 'nullable|string'
        ];
    }

    public function messages()
    {
        return [
            'receiver_id.required' => 'Alıcı kimliği gerekli.',
            'receiver_id.exists' => 'Alıcı mevcut değil.',
            'content.required' => 'Mesaj içeriği boş bırakılamaz.',
            'content.max' => 'Mesaj içeriği 1000 karakteri aşamaz.'
        ];
    }
}
