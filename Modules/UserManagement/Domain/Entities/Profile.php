<?php


namespace Modules\UserManagement\Domain\Entities;

use Modules\Shared\Domain\ValueObjects\UserId;
use Modules\UserManagement\Domain\ValueObjects\Avatar;
use Modules\UserManagement\Domain\ValueObjects\Bio;
use Modules\UserManagement\Domain\ValueObjects\PhoneNumber;
use Modules\UserManagement\Domain\ValueObjects\ProfileId;

final class Profile
{
    public function __construct(
        private readonly ProfileId $id,
        private readonly UserId $userId,
        private Avatar $avatar,
        private Bio $bio,
        private PhoneNumber $phone,
        private string $location,
    ) {
    }

    public function id(): ProfileId
    {
        return $this->id;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function avatar(): Avatar
    {
        return $this->avatar;
    }

    public function setAvatar(Avatar $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function bio(): Bio
    {
        return $this->bio;
    }

    public function setBio(Bio $bio): void
    {
        $this->bio = $bio;
    }

    public function phone(): PhoneNumber
    {
        return $this->phone;
    }

    public function setPhone(PhoneNumber $phone): void
    {
        $this->phone = $phone;
    }

    public function location(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }


}
