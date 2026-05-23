<?php

namespace Modules\UserManagement\Application\DTOs\Outputs;

use Modules\Authentication\Domain\ValueObjects\Email;
use Modules\Shared\Domain\ValueObjects\UserId;
use Modules\UserManagement\Domain\ValueObjects\Avatar;
use Modules\UserManagement\Domain\ValueObjects\Bio;
use Modules\UserManagement\Domain\ValueObjects\PhoneNumber;
use Modules\UserManagement\Domain\ValueObjects\ProfileId;

class ProfileOutputDto
{
    public function __construct(
        public ProfileId $profileId,
        public UserId $userId,
        public string $name,
        public Email $email,
        public Avatar $avatar,
        public Bio $bio,
        public PhoneNumber $phone,
        public string $location,
        public ?array $roles,
        public ?array $permissions,
    ) {
    }
}
