<?php

namespace Modules\Authentication\Infrastructure\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Modules\Authentication\Application\Actions\CheckLoginTimeAction;

#[Signature('logout-users-not-allowed-time')]
#[Description('Logout users outside allowed time')]
class LogoutTimeCommand extends Command
{
    public function __construct(
        private readonly CheckLoginTimeAction $action
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $logoutUsersDto = $this->action->execute();

        $this->info('Users logged out successfully.');

        Log::info('Force logout job completed', [
            'count' => count($logoutUsersDto->users),
            'user_emails' => array_map(fn($u) => $u->email()->value(), $logoutUsersDto->users),
        ]);
        return self::SUCCESS;
    }
}
