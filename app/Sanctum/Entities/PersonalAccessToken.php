<?php

declare(strict_types=1);

namespace App\Sanctum\Entities;

use App\Entities\User;
use App\Sanctum\Contract\ApiTokenContract;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Sowl\JsonApi\Concerns\HasTimestamps;

#[ORM\Entity]
#[ORM\Table(name: 'personal_access_tokens')]
class PersonalAccessToken implements ApiTokenContract
{
    use HasTimestamps;

    #[ORM\Id]
    #[ORM\Column(type: Types::BIGINT)]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'tokens')]
    private ?User $user = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $name;

    #[ORM\Column(type: Types::STRING, length: 64, unique: true)]
    private string $token;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $abilities = [];

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, name: 'last_used_at')]
    private ?DateTimeInterface $lastUsedAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, name: 'expires_at')]
    private ?DateTimeInterface $expiresAt = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;
        return $this;
    }

    public function getAbilities(): array
    {
        return $this->abilities;
    }

    public function setAbilities(array $abilities): static
    {
        $this->abilities = $abilities;
        return $this;
    }

    public function getLastUsedAt(): ?DateTimeInterface
    {
        return $this->lastUsedAt;
    }

    public function setLastUsedAt(?DateTimeInterface $lastUsedAt): static
    {
        $this->lastUsedAt = $lastUsedAt;
        return $this;
    }

    public function getExpiresAt(): ?DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?DateTimeInterface $expiresAt): static
    {
        $this->expiresAt = $expiresAt;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getTokenable(): ?User
    {
        return $this->user;
    }

    /**
     * Determine if the token has a given ability.
     *
     * @param  string  $ability
     * @return bool
     */
    public function can($ability)
    {
        return in_array('*', $this->getAbilities()) ||
               array_key_exists($ability, array_flip($this->getAbilities()));
    }

    /**
     * Determine if the token is missing a given ability.
     *
     * @param  string  $ability
     * @return bool
     */
    public function cant($ability)
    {
        return ! $this->can($ability);
    }
}
