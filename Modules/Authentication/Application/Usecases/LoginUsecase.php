<?php

namespace Modules\Authentication\Application\Usecases;

use Modules\Authentication\Infrastructure\Services\GetTimeNowService;

class LoginUsecase
{
    public function __construct(
        protected GetTimeNowService $getTimeNowService
    ) {
    }


    public function execute($loginInputDto)
    {

//        $result = $this->authService->login($email->value, $loginInputDto->password);
//
//        $timeNow = [
//            'Landon' => $this->getTimeNowService->getTimeNow('Europe/London'),
//            'Turkey' => $this->getTimeNowService->getTimeNow('Africa/Cairo'),
//            'Egypt' => $this->getTimeNowService->getTimeNow('Europe/Istanbul'),
//        ];
//
//        $loginResult = new LoginResult(
//            $result['user'],
//            $result['token'],
//            $timeNow
//        );
//
//        event(new UserLoggedIn($result['user'],));
//
//        return new AuthOutputDto(
//            $loginResult->getUser(),
//            $loginResult->getToken(),
//            $loginResult->getTimeNow()
//        );
    }
}
