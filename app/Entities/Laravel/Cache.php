<?php

namespace App\Entities\Laravel;

use Doctrine\ORM\Mapping as ORM;

/**
 * INTERNAL LARAVEL ENTITY (DO NOT MODIFY)
 *
 * This entity exists solely to allow Doctrine migrations for the `cache` table.
 * It is not intended for direct use or modification. Schema and logic should match Laravel's default.
 */
#[ORM\Entity]
#[ORM\Table(name: "cache")]
final class Cache
{
    #[ORM\Id]
    #[ORM\Column(type: "string")]
    private string $key;

    #[ORM\Column(type: "text")]
    private string $value;

    #[ORM\Column(type: "integer")]
    private int $expiration;

    public function getKey(): string
    {
        return $this->key;
    }
    public function setKey(string $key): self
    {
        $this->key = $key;
        return $this;
    }
    public function getValue(): string
    {
        return $this->value;
    }
    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }
    public function getExpiration(): int
    {
        return $this->expiration;
    }
    public function setExpiration(int $expiration): self
    {
        $this->expiration = $expiration;
        return $this;
    }
}
