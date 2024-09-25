<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class BaseRequest extends FormRequest
{
   /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    // /**
    //  * Get the validation rules that apply to the request.
    //  *
    //  * @return array
    //  */
    // public function rules()
    // {
    //     return [];
    // }

    // /**
    //  * Get custom messages for validator errors.
    //  *
    //  * @return array
    //  */
    // public function messages()
    // {
    //     return [];
    // }

    /**
     * Handle a failed validation attempt.
     *
     * @param  Validator  $validator
     * @return void
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();
        $errorString = implode(' ', $errors);
        $response = [
            "success" => false,
            'data' => null,
            'message' => 'Validation failed.',
            'errors' => $errorString,
        ];

        throw new HttpResponseException(response()->json($response, 400));
    }
}
