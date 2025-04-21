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

class UserController extends Controller
{
    /**
     * Return the authenticated user's profile using an Action class.
     */
    public function me(Request $request): Response
    {
        return UserMeAction::create()->dispatch($request);
    }

    /**
     * Create a new user with validation.
     */
    public function create(UserCreateRequest $request): Response
    {
        return UserCreateAction::create()->dispatch($request);
    }

    /**
     * Update an existing user with validation.
     */
    public function update(UserUpdateRequest $request): Response
    {
        return UserUpdateAction::create()->dispatch($request);
    }
}
