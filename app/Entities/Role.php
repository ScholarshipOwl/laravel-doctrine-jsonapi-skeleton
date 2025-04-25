<?php

declare(strict_types=1);

namespace App\Entities;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManager;
use LaravelDoctrine\ACL\Attribute as ACL;
use LaravelDoctrine\ACL\Contracts\HasPermissions;
use LaravelDoctrine\ACL\Contracts\Role as RoleContract;
use LaravelDoctrine\ACL\Permissions\WithPermissions;
use Sowl\JsonApi\ResourceInterface;
use Sowl\JsonApi\Relationships\MemoizeRelationshipsTrait;
use Sowl\JsonApi\Relationships\RelationshipsCollection;
use App\Transformers\RoleTransformer;
use App\Entities\User;

#[ORM\Entity]
#[ORM\Table(name: "roles")]
class Role implements RoleContract, HasPermissions, ResourceInterface
{
    use WithPermissions;
    use MemoizeRelationshipsTrait;

    public const ADMIN = 'admin';

    public static function getResourceType(): string
    {
        return 'roles';
    }

    public static function transformer(): RoleTransformer
    {
        return new RoleTransformer();
    }

    public static function relationships(): RelationshipsCollection
    {
        return static::memoizeRelationships();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private string $name;

    #[ACL\HasPermissions()]
    private array $permissions;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'roles', fetch: 'EXTRA_LAZY')]
    private Collection $users; // Added it only for the mapping

    public static function admin(): static
    {
        return app(EntityManager::class)
            ->getRepository(Role::class)
            ->findOneBy(['name' => self::ADMIN]);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /** @return array<string> */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /** @return array<string> */
    public function setPermissions(array $permissions): self
    {
        $this->permissions = $permissions;
        return $this;
    }

    public function addPermission(string $permission): self
    {
        if (!in_array($permission, $this->permissions)) {
            $this->permissions[] = $permission;
        }

        return $this;
    }

    public function removePermission(string $permission): self
    {
        $this->permissions = array_filter($this->permissions, fn($p) => $p !== $permission);
        return $this;
    }
}
