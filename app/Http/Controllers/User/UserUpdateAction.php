<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Hash;
use Sowl\JsonApi\AbstractAction;
use Sowl\JsonApi\Response;

/**
 * @property UserUpdateRequest $request
 * @method UserUpdateRequest request()
 */
class UserUpdateAction extends AbstractAction
{
    /**
     * Handle the user update request.
     */
    public function handle(): Response
    {
        $user = $this->request()->resource();
        $attributes = $this->request()->validated('data.attributes');

        if (isset($attributes['email'])) {
            $user->setEmail($attributes['email']);
        }
        if (isset($attributes['name'])) {
            $user->setName($attributes['name']);
        }
        if (isset($attributes['password'])) {
            $user->setPassword(Hash::make($attributes['password']));
        }

        $this->repository()->em()->flush();

        return $this->response()->item($user);
    }
}
