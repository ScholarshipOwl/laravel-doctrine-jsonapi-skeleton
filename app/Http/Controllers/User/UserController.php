<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\User\UserMeAction;
use Sowl\JsonApi\Request as JsonApiRequest;
use App\Http\Controllers\Controller;
use Sowl\JsonApi\Response;

class UserController extends Controller
{
    /**
     * Return the authenticated user's profile using an Action class.
     */
    public function me(JsonApiRequest $request): Response
    {
        return UserMeAction::create()->dispatch($request);
    }
}
