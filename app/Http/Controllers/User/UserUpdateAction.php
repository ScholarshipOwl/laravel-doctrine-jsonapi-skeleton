<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Hash;
use Sowl\JsonApi\AbstractAction;
use Sowl\JsonApi\Default\AbilitiesInterface;
use Sowl\JsonApi\Response;

class UserUpdateAction extends AbstractAction
{
    public function __construct(protected UserUpdateRequest $request)
    {
    }

    public function authorize(): bool
    {
        return $this->request->user()->can(AbilitiesInterface::UPDATE, $this->request->resource());
    }

    public function handle(): Response
    {
        $user = $this->request->resource();
        $attributes = $this->request->validated('data.attributes');

        if (isset($attributes['email'])) {
            $user->setEmail($attributes['email']);
        }
        if (isset($attributes['name'])) {
            $user->setName($attributes['name']);
        }
        if (isset($attributes['password'])) {
            $user->setPassword(Hash::make($attributes['password']));
        }

        $this->em()->flush();

        return $this->response()->item($user);
    }
}
