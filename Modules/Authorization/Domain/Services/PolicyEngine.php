<?php

namespace Modules\Authorization\Domain\Services;

use Modules\Authorization\Domain\Contracts\PolicyEngineInterface;
use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;
use Modules\Authorization\Domain\Entities\Permission;
use Modules\Authorization\Domain\ValueObjects\FieldPermissions;
use Modules\Authorization\Domain\ValueObjects\ResourceAttributes;
use Modules\Shared\Domain\ValueObjects\UserId;

class PolicyEngine implements PolicyEngineInterface
{
    public function __construct(private readonly RoleRepositoryInterface $roleRepository)
    {
    }

    public function evaluate(UserId $userId, Permission $permission, ResourceAttributes $resource): bool
    {

        //1. load the role for user
        $roles = $this->roleRepository->findByUserId($userId);

        if (empty($roles)) {
            return false; // No Role ->deny everything
        }
        //2. Does the role have this permission at all?

        $rolePermission = null;
        foreach ($roles as $role) {
            $rolePermission = $role->findPermission($permission->name());
            if ($rolePermission !== null) {
                break; // stop early
            }
        }
        if ($rolePermission === null) {
            return false;
        }

        // 3. Evaluate conditions (ABAC part)
        $conditions = $rolePermission->conditions();
        if ($conditions->isEmpty()) {
            return true;  // No conditions plain RBAC (allow)
        }
        // --- Condition: owner_only ---
        // The user can only act on resources they own.
        if ($conditions->get('owner_only') === true) {
            $ownerId = $resource->get('owner_id');
            if ($ownerId === null || (int) $ownerId !== $userId->value()) {
                return false;
            }
        }
        // --- Condition: allowed_statuses ---
        // The resource must be in one of the allowed statuses
        if ($conditions->has('allowed_statuses')) {
            $allowedStatuses = (array) $conditions->get('allowed_statuses');
            $resourceStatus = $resource->get('status');
            if (!in_array($resourceStatus, $allowedStatuses, true)) {
                return false;
            }
        }
        // --- Condition: max amount ---
        // Numeric ceiling check (e.g. for financial resources).
        if ($conditions->has('max_amount')) {
            $maxAmount = (float) $conditions->get('max_amount');
            $resourceAmount = $resource->get('amount', 0);
            if ($resourceAmount > $maxAmount) {
                return false;
            }
        }

        // Add more condition evaluators here as your system grows.
        // Each condition must be self-contained and easy to unit-test.

        return true;
    }

    public function resolveFieldPermissions(UserId $userId, string $permissionName): FieldPermissions
    {
        $roles = $this->roleRepository->findByUserId($userId);

        if (empty($roles)) {
            return FieldPermissions::none();
        }
        $permission = null;
        foreach ($roles as $role) {
            $foundPermission = $role->findPermission($permissionName);
            if ($foundPermission !== null) {
                $permission = $foundPermission;
                break;
            }
        }
        if ($permission === null) {
            return FieldPermissions::none();
        }
        return FieldPermissions::fromConditions($permission->conditions());
    }
}
