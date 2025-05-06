<?php

namespace App\Sanctum;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use App\Sanctum\Entities\PersonalAccessToken;

class NewAccessToken implements Arrayable, Jsonable
{
    public function __construct(
        public PersonalAccessToken $accessToken,
        public string $plainTextToken
    ) {
        $this->accessToken = $accessToken;
        $this->plainTextToken = $plainTextToken;
    }

    /**
     * @return array<string, string>
     */
    public function toArray()
    {
        return [
            'accessToken' => $this->accessToken,
            'plainTextToken' => $this->plainTextToken,
        ];
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
