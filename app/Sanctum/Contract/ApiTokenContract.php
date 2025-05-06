<?php

namespace App\Sanctum\Contract;

use DateTimeInterface;
use Laravel\Sanctum\Contracts\HasAbilities;
use Laravel\Sanctum\Contracts\HasApiTokens;

interface ApiTokenContract extends HasAbilities
{
    public function getTokenable(): ?HasApiTokens;

    public function getToken(): string;

    public function getCreatedAt(): ?DateTimeInterface;

    public function setLastUsedAt(DateTimeInterface $updatedAt): static;

    public function getLastUsedAt(): ?DateTimeInterface;

    public function getExpiresAt(): ?DateTimeInterface;
}
