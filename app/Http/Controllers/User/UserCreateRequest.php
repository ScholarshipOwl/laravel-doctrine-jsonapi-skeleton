<?php

namespace App\Http\Controllers\User;

use App\Entities\User;
use Illuminate\Validation\Rule;
use Sowl\JsonApi\Request;

/**
 * @extends Request<User>
 */
class UserCreateRequest extends Request
{
    /**
     * Validation rules for the 'data' part of the request (JSON:API).
     */
    public function dataRules(): array
    {
        return [
            'data.type' => ['required', 'in:users'],
            'data.attributes.email' => ['required', 'email', Rule::unique(User::class, 'email')],
            'data.attributes.password' => ['required', 'string', 'min:8'],
            'data.attributes.name' => ['required', 'string', 'max:255'],
        ];
    }
}
