<?php

declare(strict_types=1);

namespace App\Sanctum;

use App\Sanctum\Contract\ApiTokenContract;
use Laravel\Sanctum\Contracts\HasApiTokens;
use DateTimeInterface;

class TransientToken implements ApiTokenContract
{
    public function __construct(protected HasApiTokens $tokenable)
    {
    }

    public function getToken(): string
    {
        return '';
    }

    public function getTokenable(): ?HasApiTokens
    {
        return $this->tokenable;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return null;
    }

    public function setLastUsedAt(DateTimeInterface $updatedAt): static
    {
        return $this;
    }

    public function getLastUsedAt(): ?DateTimeInterface
    {
        return null;
    }

    public function getExpiresAt(): ?DateTimeInterface
    {
        return null;
    }

    public function can($ability): bool
    {
        return true;
    }

    public function cant($ability): bool
    {
        return false;
    }
}
