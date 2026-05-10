<?php

namespace Modules\Authentication\Application\Actions;

use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;
use Modules\Shared\Domain\ValueObjects\UserId;

class CheckCanStayLoginAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function execute(int $userId): bool
    {
        $user = $this->userRepository->findById(new UserId($userId));

        $checkLoginTime = $user->verifyLoginTime(new \DateTimeImmutable('now'));

        if (!$checkLoginTime) {
            return false;
        }
        return true;
    }
}
