<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\User\UserMeAction;
use App\Http\Controllers\User\UserCreateRequest;
use App\Http\Controllers\User\UserCreateAction;
use App\Http\Controllers\User\UserUpdateAction;
use App\Http\Controllers\User\UserUpdateRequest;
use App\Http\Controllers\Controller;
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
        return UserMeAction::create()->dispatch($request);
    }

    #[ResourceRequestCreate]
    #[ResourceResponse]
    public function create(UserCreateRequest $request): Response
    {
        return UserCreateAction::create()->dispatch($request);
    }

    #[ResourceRequest]
    #[ResourceResponse]
    public function update(UserUpdateRequest $request): Response
    {
        return UserUpdateAction::create()->dispatch($request);
    }
}
