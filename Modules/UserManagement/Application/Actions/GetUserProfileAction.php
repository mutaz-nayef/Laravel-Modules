<?php

namespace Modules\UserManagement\Application\Actions;

use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;
use Modules\Authentication\Domain\Exceptions\UserNotFoundException;
use Modules\UserManagement\Application\DTOs\Inputs\GetProfileInputDto;
use Modules\UserManagement\Application\DTOs\Outputs\ProfileOutputDto;
use Modules\UserManagement\Domain\Contracts\ProfileRepositoryInterface;
use Modules\UserManagement\Domain\Exceptions\ProfileNotFoundException;

class GetUserProfileAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly ProfileRepositoryInterface $profileRepository
    ) {
    }

    /**
     * @throws UserNotFoundException
     */
    public function execute(GetProfileInputDto $input): ProfileOutputDto
    {
        $user = $this->userRepository->findById($input->userId);
        if (!$user) {
            throw new UserNotFoundException('User not found', 404);
        }

        $profile = $this->profileRepository->getByUserId($user->id());

        if (!$profile) {
            throw new ProfileNotFoundException('Profile not found', 404);
        }
        
        return new ProfileOutputDto(
            profileId: $profile->id(),
            userId: $user->id(),
            name: $user->name(),
            email: $user->email(),
            avatar: $profile->avatar(),
            bio: $profile->bio(),
            phone: $profile->phone(),
            location: $profile->location(),
            roles: $user->roles(),
            permissions: $user->permissions(),
        );
    }
}
