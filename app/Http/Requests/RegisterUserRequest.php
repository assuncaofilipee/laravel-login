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
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
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


    public function messages(): array
    {
        return [
            'terms_of_use.required' => 'O campo termos de uso é obrigatório.',
            'terms_of_use.in' => 'É obrigatório o aceite dos Termos de uso'
        ];
    }

    public function expectsJson(): bool
    {
        return true;
    }

    public function failedValidation(Validator $validator): void
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json(['success' => 'false', 'data' => $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
