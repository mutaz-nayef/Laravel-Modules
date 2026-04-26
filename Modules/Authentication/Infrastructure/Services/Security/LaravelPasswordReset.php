<?php

namespace Modules\Authentication\Infrastructure\Services\Security;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\Authentication\Domain\Contracts\PasswordResetInterface;
use Modules\Authentication\Domain\ValueObjects\Email;
use Modules\Authentication\Domain\ValueObjects\HashedPassword;
use Modules\Authentication\Domain\ValueObjects\IssuedToken;
use Modules\Authentication\Infrastructure\Models\UserModel;

class LaravelPasswordReset implements PasswordResetInterface
{
    public function __construct(protected Password $password)
    {
    }

    public function sendResetLink(Email $email): string
    {
        $status = $this->password::sendResetLink(['email' => $email->value()]);
        if ($status != $this->password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }
        return $status;
    }

    public function reset(IssuedToken $token, Email $email, HashedPassword $password): string
    {
        $status = $this->password::reset(
            [
                'email' => $email->value(),
                'token' => $token->plainText(),
                'password' => $password->hash(),
            ],
            function (UserModel $user, string $passwordHashed) {
                $user->forceFill([
                    'password' => $passwordHashed,
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );
        if ($status != $this->password::PASSWORD_RESET) {
            throw   ValidationException::withMessages([
                'token' => [__($status)],
            ])->status(422);
        }
        return $status;
    }
}
