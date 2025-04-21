<?php

namespace App\Http\Controllers\User;

use App\Entities\User;
use Sowl\JsonApi\Request;

/**
 * @extends Request<User>
 */
class UserUpdateRequest extends Request
{
    /**
     * Validation rules for updating a user.
     */
    public function dataRules(): array
    {
        return [
            'data.attributes.email' => ['sometimes', 'email', 'max:255'],
            'data.attributes.password' => ['sometimes', 'string', 'min:6'],
            'data.attributes.name' => ['sometimes', 'string', 'max:255'],
        ];
    }
}
