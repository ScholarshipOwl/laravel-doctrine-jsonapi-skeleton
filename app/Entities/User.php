<?php

namespace App\Entities;

use App\Transformers\UserTransformer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use LaravelDoctrine\ACL\Attribute as ACL;
use LaravelDoctrine\ACL\Contracts\HasPermissions;
use LaravelDoctrine\ACL\Contracts\HasRoles;
use LaravelDoctrine\ACL\Permissions\WithPermissions;
use LaravelDoctrine\ACL\Roles\WithRoles;
use LaravelDoctrine\ORM\Auth\Authenticatable;
use LaravelDoctrine\ORM\Notifications\Notifiable;
use Sowl\JsonApi\AbstractTransformer;
use Sowl\JsonApi\Concerns\HasTimestamps;
use Sowl\JsonApi\Relationships\MemoizeRelationshipsTrait;
use Sowl\JsonApi\Relationships\RelationshipsCollection;
use Sowl\JsonApi\ResourceInterface;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract,
    HasPermissions,
    HasRoles,
    ResourceInterface
{
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use HasTimestamps;
    use MemoizeRelationshipsTrait;
    use Notifiable;
    use WithPermissions;
    use WithRoles;

    public static function getResourceType(): string
    {
        return 'users';
    }

    public static function relationships(): RelationshipsCollection
    {
        return static::memoizeRelationships()
            ->addToMany('roles', Role::class, 'users');
    }

    public static function transformer(): AbstractTransformer
    {
        return new UserTransformer();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $email;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $emailVerifiedAt = null;

    #[ACL\HasPermissions()]
    private array $permissions = [];

    #[ACL\HasRoles(inversedBy: 'users')]
    private Collection $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEmailVerifiedAt(): ?\DateTime
    {
        return $this->emailVerifiedAt;
    }

    public function setEmailVerifiedAt(?\DateTime $emailVerifiedAt): self
    {
        $this->emailVerifiedAt = $emailVerifiedAt;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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
        if (! in_array($permission, $this->permissions)) {
            $this->permissions[] = $permission;
        }

        return $this;
    }

    public function removePermission(string $permission): self
    {
        $this->permissions = array_filter($this->permissions, fn ($p) => $p !== $permission);

        return $this;
    }

    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function setRoles(Collection $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(Role $role): self
    {
        if (! $this->roles->contains($role)) {
            $this->roles->add($role);
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        $this->roles->removeElement($role);

        return $this;
    }
}
