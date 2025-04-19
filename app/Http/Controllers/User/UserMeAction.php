<?php

namespace App\Http\Controllers\User;

use Sowl\JsonApi\AbstractAction;
use Sowl\JsonApi\Response;

class UserMeAction extends AbstractAction
{
    /**
     * Handle the action to show the current authenticated user.
     *
     * @return Response
     */
    public function handle(): Response
    {
        $user = $this->request()->user();

        return response()->item($user);
    }
}
