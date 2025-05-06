<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Endpoint;
use Sowl\JsonApi\Request;
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
    public function me(Request $request): Response
    {
        return UserMeAction::makeDispatch($request);
    }

    #[Endpoint(title: 'User registration', description: '**New user registration**')]
    #[ResourceRequestCreate]
    #[ResourceResponse]
    public function create(UserCreateRequest $request): Response
    {
        return (new UserCreateAction($request))->dispatch();
    }

    #[ResourceRequest]
    #[ResourceResponse]
    public function update(UserUpdateRequest $request): Response
    {
        return (new UserUpdateAction($request))->dispatch();
    }
}
