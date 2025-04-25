<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Entities\Role;
use App\Entities\User;
use Sowl\JsonApi\AbstractTransformer;

class RoleTransformer extends AbstractTransformer
{
    protected array $availableIncludes = [];

    public function transform(Role $role): array
    {
        return [
            'id' => $role->getId(),
            'name' => $role->getName(),
            'permissions' => $role->getPermissions(),
        ];
    }
}
