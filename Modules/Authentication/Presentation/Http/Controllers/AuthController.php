<?php

namespace Modules\Authentication\Presentation\Http\Controllers;

use Modules\Authentication\Application\Actions\LoginUserAction;
use Modules\Authentication\Application\Actions\LogoutUserAction;
use Modules\Authentication\Application\Actions\RegisterUserAction;
use Modules\Authentication\Application\DTO\Auth\Input\loginInputDto;
use Modules\Authentication\Application\DTO\Auth\Input\LogoutInputDto;
use Modules\Authentication\Application\DTO\Auth\Input\RegisterInputDto;
use Modules\Authentication\Presentation\Http\Requests\LoginRequest;
use Modules\Authentication\Presentation\Http\Requests\LogoutRequest;
use Modules\Authentication\Presentation\Http\Requests\RegisterRequest;
use Modules\Authentication\Presentation\Http\Resources\AuthResource;
use Modules\Shared\Domain\ValueObjects\UserId;
use Modules\Shared\Presentation\Http\Controllers\BaseController;

class AuthController extends BaseController
{
    public function __construct(
        private readonly LoginUserAction $loginUserAction,
        private readonly LogoutUserAction $logoutUserAction,
        private readonly RegisterUserAction $registerUserAction,
    ) {
    }

    public function login(LoginRequest $request)
    {
        return $this->handle(function () use ($request) {

            $output = $this->loginUserAction->execute(
                new LoginInputDto(
                    email: $request->input('email'),
                    password: $request->input('password')
                )
            );
            return [
                'message' => 'Authenticated',
                'data' => new AuthResource($output)
            ];
        });
    }

    public function register(RegisterRequest $request)
    {
        return $this->handle(function () use ($request) {

            $output = $this->registerUserAction->execute(
                new RegisterInputDto(
                    name: $request->input('name'),
                    email: $request->input('email'),
                    password: $request->input('password')
                )
            );

            return [
                'message' => 'Authenticated',
                'data' => new AuthResource($output)
            ];
        });
    }

    public function logout(LogoutRequest $request)
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
