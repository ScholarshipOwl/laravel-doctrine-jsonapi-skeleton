<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @extends FormRequest
 */
class RequestResetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
        ];
    }
}
