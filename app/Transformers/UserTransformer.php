<?php

namespace App\Transformers;

use App\Entities\User;
use Sowl\JsonApi\AbstractTransformer;

class UserTransformer extends AbstractTransformer
{
    public function transform(User $user): array
    {
        return [
            'id' => (string) $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            // Add more attributes as needed
        ];
    }
}
