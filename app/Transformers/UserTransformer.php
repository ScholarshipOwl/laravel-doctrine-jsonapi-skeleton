<?php

namespace App\Transformers;

use App\Entities\User;
use Sowl\JsonApi\AbstractTransformer;
use Sowl\JsonApi\ResourceInterface;

class UserTransformer extends AbstractTransformer
{
    /**
     * @param User $resource
     * @return array
     */
    public function transform(ResourceInterface $resource): array
    {
        /** @var User $resource */
        return [
            'id' => (string) $resource->getId(),
            'name' => $resource->getName(),
            'email' => $resource->getEmail(),
            // Add more attributes as needed
        ];
    }
}
