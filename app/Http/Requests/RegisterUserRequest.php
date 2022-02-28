<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;


class RegisterUserRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|string|email|confirmed|max:100|unique:users',
            'password' => [
                'required',
                'confirmed',
                'max:45',
                Password::min(8)
                    ->letters()
                    ->numbers()
            ],
            'terms_of_use' => 'required|in:true'
        ];
    }


    public function messages()
    {
        return [
            'terms_of_use.required' => 'O campo termos de uso é obrigatório.',
            'terms_of_use.in' => 'É obrigatório o aceite dos Termos de uso'
        ];
    }

    public function expectsJson()
    {
        return true;
    }

    public function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json(['success' => 'false', 'data' => $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }

}
