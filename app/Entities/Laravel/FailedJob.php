<?php

namespace App\Entities\Laravel;

use Doctrine\ORM\Mapping as ORM;

/**
 * INTERNAL LARAVEL ENTITY (DO NOT MODIFY)
 *
 * This entity exists solely to allow Doctrine migrations for the `failed_jobs` table.
 * It is not intended for direct use or modification. Schema and logic should match Laravel's default.
 */
#[ORM\Entity]
#[ORM\Table(name: "failed_jobs")]
final class FailedJob
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private int $id;

    #[ORM\Column(type: "string", unique: true)]
    private string $uuid;

    #[ORM\Column(type: "text")]
    private string $connection;

    #[ORM\Column(type: "text")]
    private string $queue;

    #[ORM\Column(type: "text")]
    private string $payload;

    #[ORM\Column(type: "text")]
    private string $exception;

    #[ORM\Column(type: "datetime_immutable")]
    private \DateTimeImmutable $failedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;
        return $this;
    }

    public function getConnection(): string
    {
        return $this->connection;
    }

    public function setConnection(string $connection): self
    {
        $this->connection = $connection;
        return $this;
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

    public function getException(): string
    {
        return $this->exception;
    }

    public function setException(string $exception): self
    {
        $this->exception = $exception;
        return $this;
    }

    public function getFailedAt(): \DateTimeImmutable
    {
        return $this->failedAt;
    }

    public function setFailedAt(\DateTimeImmutable $failedAt): self
    {
        $this->failedAt = $failedAt;
        return $this;
    }
}
