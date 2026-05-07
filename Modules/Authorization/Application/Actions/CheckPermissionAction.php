<?php

namespace Modules\Authorization\Application\Actions;

use Modules\Authorization\Application\DTOs\Input\CheckPermissionInputDto;
use Modules\Authorization\Application\DTOs\Output\CheckPermissionOutputDto;
use Modules\Authorization\Domain\Contracts\PermissionRepositoryInterface;
use Modules\Authorization\Domain\Contracts\PolicyEngineInterface;

class CheckPermissionAction
{
    public function __construct(
        private readonly PolicyEngineInterface $policyEngine,
        private readonly PermissionRepositoryInterface $permissionRepository,
    ) {
    }

    public function execute(CheckPermissionInputDto $input): CheckPermissionOutputDto
    {
        // 1. Load the full permission entity (with conditions) for this user
        $permission = $this->permissionRepository->findForUser(
            $input->userId->value(),
            $input->permissionName
        );

        if ($permission === null) {
            return new CheckPermissionOutputDTO(
                allowed: false,
                reason: "Permission '{$input->permissionName}' not assigned.",
            );
        }
        //2. Run ABAC evaluation
        $allowed = $this->policyEngine->evaluate(
            $input->userId,
            $permission,
            $input->resource
        );
        return new CheckPermissionOutputDTO(
            allowed: $allowed,
            reason: $allowed ? "Allowed by policy." : "Denied by policy conditions.",
        );
    }
}
