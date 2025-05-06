<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

// Changed namespace

use Illuminate\Foundation\Http\FormRequest;

class CreateTokenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Anyone can attempt to create a token
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'deviceName' => ['required', 'string'],
            'abilities' => ['sometimes', 'array'],
            'expiresAt' => ['sometimes', 'date'],
        ];
    }
}
