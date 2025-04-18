<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "password_reset_tokens")]
class PasswordResetToken
{
    #[ORM\Id]
    #[ORM\Column(type: "string")]
    private string $email;

    #[ORM\Column(type: "string")]
    private string $token;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTime $createdAt = null;

    // Getters and setters
    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
