<?php

namespace Modules\Authorization\Application\Actions\Permission;


use Modules\Authorization\Application\DTOs\PermissionDto;
use Modules\Authorization\Domain\Contracts\PermissionRepositoryInterface;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class PermissionGetAction
{
    public function __construct(
        protected PermissionRepositoryInterface $permissionRepository
    ) {

    }

    public function execute(): array
    {
        $output = $this->permissionRepository->getAll();
        if (!$output) {
            throw new NotFoundResourceException('No Resource Found', 404);
        }
        return array_map(
            fn($permission) => new PermissionDto(
                id: $permission->id(),
                name: $permission->name(),
                group: $permission->group()
            ),
            $output
        );
    }
}
