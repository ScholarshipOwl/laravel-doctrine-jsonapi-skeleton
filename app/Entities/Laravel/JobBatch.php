<?php

namespace App\Entities\Laravel;

use Doctrine\ORM\Mapping as ORM;

/**
 * INTERNAL LARAVEL ENTITY (DO NOT MODIFY)
 *
 * This entity exists solely to allow Doctrine migrations for the `job_batches` table.
 * It is not intended for direct use or modification. Schema and logic should match Laravel's default.
 */
#[ORM\Entity]
#[ORM\Table(name: 'job_batches')]
final class JobBatch
{
    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    private string $id;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'integer')]
    private int $totalJobs;

    #[ORM\Column(type: 'integer')]
    private int $pendingJobs;

    #[ORM\Column(type: 'integer')]
    private int $failedJobs;

    #[ORM\Column(type: 'text')]
    private string $failedJobIds;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $options = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $cancelledAt = null;

    #[ORM\Column(type: 'integer')]
    private int $createdAt;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $finishedAt = null;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
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

    public function getTotalJobs(): int
    {
        return $this->totalJobs;
    }

    public function setTotalJobs(int $totalJobs): self
    {
        $this->totalJobs = $totalJobs;

        return $this;
    }

    public function getPendingJobs(): int
    {
        return $this->pendingJobs;
    }

    public function setPendingJobs(int $pendingJobs): self
    {
        $this->pendingJobs = $pendingJobs;

        return $this;
    }

    public function getFailedJobs(): int
    {
        return $this->failedJobs;
    }

    public function setFailedJobs(int $failedJobs): self
    {
        $this->failedJobs = $failedJobs;

        return $this;
    }

    public function getFailedJobIds(): string
    {
        return $this->failedJobIds;
    }

    public function setFailedJobIds(string $failedJobIds): self
    {
        $this->failedJobIds = $failedJobIds;

        return $this;
    }

    public function getOptions(): ?string
    {
        return $this->options;
    }

    public function setOptions(?string $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function getCancelledAt(): ?int
    {
        return $this->cancelledAt;
    }

    public function setCancelledAt(?int $cancelledAt): self
    {
        $this->cancelledAt = $cancelledAt;

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

    public function getFinishedAt(): ?int
    {
        return $this->finishedAt;
    }

    public function setFinishedAt(?int $finishedAt): self
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }
}
