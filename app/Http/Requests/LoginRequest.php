<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
        ];
    }

    /**
     * Handle failed validation.
     */
    protected function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors();

        throw new HttpResponseException(
            response()->json(
                [
                    'errors' => collect($errors->messages())
                        ->flatten()
                        ->map(
                            fn ($value) => [
                                'status' => '422',
                                'title' => 'Unprocessable Entity',
                                'description' => is_array($value) ? $value[0] : $value,
                            ]
                        ),
                ],
                422
            )
        );
    }
}
