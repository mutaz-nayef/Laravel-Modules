<?php

namespace Modules\Authentication\Infrastructure\Services\Security;

use InvalidArgumentException;
use Laravel\Sanctum\PersonalAccessToken;
use Modules\Authentication\Domain\Contracts\TokenIssuerInterface;
use Modules\Authentication\Domain\ValueObjects\IssuedToken;
use Modules\Authentication\Infrastructure\Models\UserModel;
use Modules\Shared\Domain\ValueObjects\UserId;

class LaravelSanctumToken implements TokenIssuerInterface
{

    public function refresh($refreshToken): IssuedToken
    {

        $token = PersonalAccessToken::findToken($refreshToken);

        // check if it's valid
        if (!$token) {
            throw new InvalidArgumentException('Invalid refresh token', 401);
        }

        // expiration check
        if ($token->expires_at && $token->expires_at->isPast()) {

            $token->delete();
            throw new InvalidArgumentException('Refresh token has expired', 401);
        }

        $user = $token->tokenable;


        // delete old access tokens
        $user->tokens()
            ->where('name', 'access-token')
            ->delete();

        // create new access token

        return $this->issue(new UserId($user->id), 'access_token');
    }

    public function issue(
        UserId $userId,
        string $name,
        ?string $expiresAtInMinutes = null,
        ?array $attributes = null
    ): IssuedToken {

        $user = UserModel::findOrFail($userId->value());

        $token = $user->createToken(
            name: $name,
            // fix $user->permissions() ?? null,
            abilities: ['*'],
            expiresAt: now()->addMinutes((int) $expiresAtInMinutes ?? 600),
        )->plainTextToken;
        return new IssuedToken($token);
    }

    public function revoke(UserId $userId): void
    {
        $user = UserModel::findOrFail($userId->value());
        // revoke all tokens, full logout
        $user->tokens()->delete();
    }
}
