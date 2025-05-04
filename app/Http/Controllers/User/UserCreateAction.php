<?php

namespace App\Http\Controllers\User;

use App\Entities\User;
use Illuminate\Support\Facades\Hash;
use Sowl\JsonApi\AbstractAction;
use Sowl\JsonApi\Response;

class UserCreateAction extends AbstractAction
{
    public function __construct(protected UserCreateRequest $request)
    {
    }

    /**
     * Handle the user creation request.
     */
    public function handle(): Response
    {
        $attributes = $this->request->validated('data.attributes');

        $user = new User();
        $user->setEmail($attributes['email'])
            ->setPassword(Hash::make($attributes['password']))
            ->setName($attributes['name']);

        $this->em()->persist($user);
        $this->em()->flush();

        return $this->response()->created($user);
    }
}
