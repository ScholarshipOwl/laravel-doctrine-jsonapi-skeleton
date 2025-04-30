<?php

namespace App\Http\Controllers\User;

use App\Entities\User;
use Illuminate\Support\Facades\Hash;
use Sowl\JsonApi\AbstractAction;
use Sowl\JsonApi\Response;

/**
 * @property UserCreateRequest $request
 *
 * @method UserCreateRequest request()
 */
class UserCreateAction extends AbstractAction
{
    /**
     * Handle the user creation request.
     */
    public function handle(): Response
    {
        $attributes = $this->request()->validated('data.attributes');

        $user = new User();
        $user->setEmail($attributes['email'])
            ->setPassword(Hash::make($attributes['password']))
            ->setName($attributes['name']);

        $em = $this->repository()->em();
        $em->persist($user);
        $em->flush();

        return $this->response()->created($user);
    }
}
