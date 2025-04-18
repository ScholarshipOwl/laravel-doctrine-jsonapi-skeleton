<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "cache_locks")]
class CacheLock
{
    #[ORM\Id]
    #[ORM\Column(type: "string")]
    private string $key;

    #[ORM\Column(type: "string")]
    private string $owner;

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
    public function getOwner(): string
    {
        return $this->owner;
    }
    public function setOwner(string $owner): self
    {
        $this->owner = $owner;
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
