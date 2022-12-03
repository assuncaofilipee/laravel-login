<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;


class RegisterProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|alpha|max:50',
            'last_name' => 'required|alpha|max:100',
            'cpf' => 'required|cpf|unique:profiles'
        ];
    }


    public function messages()
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
