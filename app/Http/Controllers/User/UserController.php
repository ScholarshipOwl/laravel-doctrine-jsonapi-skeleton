<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Endpoint;
use Sowl\JsonApi\Response;
use Sowl\JsonApi\Scribe\Attributes\ResourceRequest;
use Sowl\JsonApi\Scribe\Attributes\ResourceRequestCreate;
use Sowl\JsonApi\Scribe\Attributes\ResourceResponse;

class UserController extends Controller
{
    /**
     * User Me
     *
     * Return currently authenticated user's resource.
     * Should be used with authentication logic, first login than use this endpoint.
     */
    #[ResourceRequest]
    #[ResourceResponse]
    public function me(UserMeAction $action): Response
    {
        return $action->dispatch();
    }

    #[Endpoint(title: 'User registration', description: '**New user registration**')]
    #[ResourceRequestCreate]
    #[ResourceResponse]
    public function create(UserCreateAction $action): Response
    {
        return $action->dispatch();
    }

    #[ResourceRequest]
    #[ResourceResponse]
    public function update(UserUpdateAction $action): Response
    {
        return $action->dispatch();
    }
}
