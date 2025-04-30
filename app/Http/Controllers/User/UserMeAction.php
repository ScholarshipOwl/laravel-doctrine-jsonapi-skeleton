<?php

namespace App\Http\Controllers\User;

use Sowl\JsonApi\AbstractAction;
use Sowl\JsonApi\Response;

class UserMeAction extends AbstractAction
{
    /**
     * Handle the action to show the current authenticated user.
     */
    public function handle(): Response
    {
        $user = $this->request()->user();

        return $this->response()->item($user);
    }
}
