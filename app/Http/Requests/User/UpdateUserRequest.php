<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;


class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'string|email|max:100',
            'name' => 'alpha|max:100',
            'cpf' => 'cpf|unique:users',
            'password' => [
                'max:45',
                Password::min(8)
                    ->letters()
                    ->numbers()
            ]
        ];
    }


    public function messages(): array
    {
        return [
            //
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
            response()->json(['success' => false, 'error' => $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
