<?php

namespace App\Transformers;

use App\Entities\Role;
use App\Entities\User;
use League\Fractal\Resource\ResourceInterface;
use Sowl\JsonApi\AbstractTransformer;

class UserTransformer extends AbstractTransformer
{
    public array $availableIncludes = [
        'roles',
    ];

    public function transform(User $user): array
    {
        return [
            'id' => (string) $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            // Add more attributes as needed
        ];
    }

    public function includeRoles(User $user): ResourceInterface
    {
        return $this->collection(
            $user->getRoles(),
            new RoleTransformer(),
            Role::getResourceType()
        );
    }
}
