<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * INTERNAL LARAVEL ENTITY (DO NOT MODIFY)
 *
 * This entity exists solely to allow Doctrine migrations for the `sessions` table.
 * It is not intended for direct use or modification. Schema and logic should match Laravel's default.
 */
#[ORM\Entity]
#[ORM\Table(name: "sessions")]
final class Session
{
    #[ORM\Id]
    #[ORM\Column(type: "string")]
    private string $id;

    #[ORM\Column(type: "bigint", nullable: true)]
    private ?int $userId = null;

    #[ORM\Column(type: "string", length: 45, nullable: true)]
    private ?string $ipAddress = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $userAgent = null;

    #[ORM\Column(type: "text")]
    private string $payload;

    #[ORM\Column(type: "integer")]
    private int $lastActivity;

    // Getters and setters
    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(?string $ipAddress): self
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(?string $userAgent): self
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    public function getPayload(): string
    {
        return $this->payload;
    }

    public function setPayload(string $payload): self
    {
        $this->payload = $payload;
        return $this;
    }

    public function getLastActivity(): int
    {
        return $this->lastActivity;
    }

    public function setLastActivity(int $lastActivity): self
    {
        $this->lastActivity = $lastActivity;
        return $this;
    }
}
