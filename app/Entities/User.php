<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use App\Concerns\HasTimestamps;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use LaravelDoctrine\ORM\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Sowl\JsonApi\ResourceInterface;
use Sowl\JsonApi\Relationships\RelationshipsCollection;
use Sowl\JsonApi\AbstractTransformer;
use App\Transformers\UserTransformer;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract, ResourceInterface
{
    use HasTimestamps;
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;

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

    public static function getResourceType(): string
    {
        return 'users';
    }

    public static function relationships(): RelationshipsCollection
    {
        // Return an empty collection or define relationships as needed
        return new RelationshipsCollection([]);
    }

    public static function transformer(): AbstractTransformer
    {
        return new UserTransformer();
    }
}
