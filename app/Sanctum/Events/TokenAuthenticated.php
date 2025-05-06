<?php

namespace App\Sanctum\Events;

use App\Sanctum\Contract\ApiTokenContract;

class TokenAuthenticated
{
    public function __construct(public ApiTokenContract $token)
    {
    }
}
