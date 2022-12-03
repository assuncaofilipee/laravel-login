<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class PasswordRecoveryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "email" => "required|email|exists:users,email"
        ];
    }

    public function messages(): array
    {
        return [
            "email.exists" => "E-mail não encontrado, favor revisar!"
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
