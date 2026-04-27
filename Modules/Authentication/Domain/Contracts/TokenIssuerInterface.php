<?php

namespace Modules\Authentication\Domain\Contracts;

use Modules\Authentication\Domain\ValueObjects\IssuedToken;
use Modules\Shared\Domain\ValueObjects\UserId;

Interface TokenIssuerInterface
{

  public function issue(UserId $userId, ?array $attributes = null):IssuedToken;
  public function revoke(UserId $userId):void;

}
