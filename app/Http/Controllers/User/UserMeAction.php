<?php

namespace App\Http\Controllers\User;

use Sowl\JsonApi\AbstractAction;
use Sowl\JsonApi\Request;
use Sowl\JsonApi\Response;

class UserMeAction extends AbstractAction
{
    public function __construct(protected Request $request)
    {
    }

    /**
     * Handle the action to show the current authenticated user.
     */
    public function handle(): Response
    {
        /** @var \App\Entities\User $user */
        $user = $this->request->user();

        return $this->response()->item($user);
    }
}
