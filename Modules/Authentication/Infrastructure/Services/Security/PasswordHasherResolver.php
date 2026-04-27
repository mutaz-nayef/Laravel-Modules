<?php

namespace Modules\Authentication\Infrastructure\Services\Security;

use Exception;

class PasswordHasherResolver
{
    /**
     * @throws Exception
     */
    public function resolve(): string
    {
        $driver = config('auth-module.password_hash_driver');

        $map = config('auth-module.password_hashers');

        if (!isset($map[$driver])) {
            throw new Exception("Password hash driver $driver does not exist", 404);
        }
        return $map[$driver];
    }

}
