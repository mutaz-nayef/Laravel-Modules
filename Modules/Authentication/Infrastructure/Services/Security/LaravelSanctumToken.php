<?php

namespace Modules\Authentication\Infrastructure\Services\Security;

use Modules\Authentication\Domain\Contracts\TokenIssuerInterface;
use Modules\Authentication\Domain\ValueObjects\IssuedToken;
use Modules\Authentication\Infrastructure\Models\UserModel;
use Modules\Authentication\Infrastructure\Services\PermissionService;
use Modules\Shared\Domain\ValueObjects\UserId;

class LaravelSanctumToken implements TokenIssuerInterface
{
    public function __construct(
        protected PermissionService $permissionService,
    ) {
    }

    public function issue(UserId $userId, ?array $attributes = null): IssuedToken
    {

        $user = UserModel::findOrFail($userId->value());

        $plain = $user->createToken(
            name: $user->name,
            // fix $user->permissions() ?? null,
            abilities:['*'],
            expiresAt:now()->addDays(config('auth-module.token.expiresAt')),
        )->plainTextToken;

       return new IssuedToken($plain);
    }

    public function revoke(UserId $userId): void
    {
        $user = UserModel::findOrFail($userId->value());
        // revoke all tokens, full logout
         $user->tokens()->delete();
    }
}
