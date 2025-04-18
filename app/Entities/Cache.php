<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "cache")]
class Cache
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
