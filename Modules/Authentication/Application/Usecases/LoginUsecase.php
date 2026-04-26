<?php

namespace Modules\Authentication\Application\Usecases;

use Modules\Authentication\Application\DTO\Auth\Output\AuthOutputDto;
use Modules\Authentication\Domain\Contracts\AuthInterface;
use Modules\Authentication\Domain\Events\LoginAttemptedOutsideAllowedTime;
use Modules\Authentication\Domain\Events\UserLoggedIn;
use Modules\Authentication\Domain\Exceptions\InvalidArgumentException;
use Modules\Authentication\Domain\Exceptions\LoginNotAllowedThisTimeException;
use Modules\Authentication\Domain\Services\LoginPolicy;
use Modules\Authentication\Domain\ValueObjects\Email;
use Modules\Authentication\Domain\ValueObjects\LoginResult;
use Modules\Authentication\Infrastructure\Services\GetTimeNowService;

class LoginUsecase
{
    public function __construct(
        protected GetTimeNowService $getTimeNowService
    ) {
    }


    public function execute($loginInputDto): AuthOutputDto
    {

        $result = $this->authService->login($email->value, $loginInputDto->password);

        $timeNow = [
            'Landon' => $this->getTimeNowService->getTimeNow('Europe/London'),
            'Turkey' => $this->getTimeNowService->getTimeNow('Africa/Cairo'),
            'Egypt' => $this->getTimeNowService->getTimeNow('Europe/Istanbul'),
        ];

        $loginResult = new LoginResult(
            $result['user'],
            $result['token'],
            $timeNow
        );

        event(new UserLoggedIn($result['user'],));

        return new AuthOutputDto(
            $loginResult->getUser(),
            $loginResult->getToken(),
            $loginResult->getTimeNow()
        );
    }
}
