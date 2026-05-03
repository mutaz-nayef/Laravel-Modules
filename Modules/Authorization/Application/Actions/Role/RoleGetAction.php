<?php

namespace Modules\Authorization\Application\Actions\Role;

use Modules\Authorization\Application\DTOs\RoleOutputDto;
use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class RoleGetAction
{
    public function __construct(
        protected RoleRepositoryInterface $roleRepository
    ) {
    }

    public function execute(): array
    {
        $output = $this->roleRepository->getAll();
        if (!$output) {
            throw new NotFoundResourceException('No Resource Found', 404);
        }
        return array_map(
            fn($role) => new RoleOutputDto(
                id: $role->id(),
                name: $role->name(),
                display_name: $role->displayName(),
                permissions: $role->permissions()
            ),
            $output
        );
    }
}
