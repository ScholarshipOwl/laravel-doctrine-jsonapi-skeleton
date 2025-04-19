<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * INTERNAL LARAVEL ENTITY (DO NOT MODIFY)
 *
 * This entity exists solely to allow Doctrine migrations for the `jobs` table.
 * It is not intended for direct use or modification. Schema and logic should match Laravel's default.
 */
#[ORM\Entity]
#[ORM\Table(name: "jobs")]
final class Job
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private int $id;

    #[ORM\Column(type: "string", length: 255)]
    private string $queue;

    #[ORM\Column(type: "text")]
    private string $payload;

    #[ORM\Column(type: "smallint", options: ["unsigned" => true])]
    private int $attempts;

    #[ORM\Column(type: "integer", nullable: true, options: ["unsigned" => true])]
    private ?int $reservedAt = null;

    #[ORM\Column(type: "integer", options: ["unsigned" => true])]
    private int $availableAt;

    #[ORM\Column(type: "integer", options: ["unsigned" => true])]
    private int $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getQueue(): string
    {
        return $this->queue;
    }

    public function setQueue(string $queue): self
    {
        $this->queue = $queue;
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

    public function getAttempts(): int
    {
        return $this->attempts;
    }

    public function setAttempts(int $attempts): self
    {
        $this->attempts = $attempts;
        return $this;
    }

    public function getReservedAt(): ?int
    {
        return $this->reservedAt;
    }

    public function setReservedAt(?int $reservedAt): self
    {
        $this->reservedAt = $reservedAt;
        return $this;
    }

    public function getAvailableAt(): int
    {
        return $this->availableAt;
    }

    public function setAvailableAt(int $availableAt): self
    {
        $this->availableAt = $availableAt;
        return $this;
    }

    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    public function setCreatedAt(int $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
