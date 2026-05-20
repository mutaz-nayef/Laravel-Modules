<?php

namespace Modules\Notifications\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Shared\Presentation\Http\Controllers\BaseController;

class NotificationPreferencesController extends BaseController
{
    public function index(Request $request)
    {
        return $this->handle(function () use ($request) {

            $this->logoutUserAction->execute(
                new LogoutInputDTO(
                    userId: new UserId($request->user()->id)
                )
            );
            return [
                'message' => 'Logged out successfully.',
            ];
        });
    }
}
