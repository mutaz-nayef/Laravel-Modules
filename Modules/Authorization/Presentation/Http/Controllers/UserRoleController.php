<?php

namespace Modules\Authorization\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Authentication\Presentation\Http\Resources\UserResource;
use Modules\Authorization\Application\Actions\UserRole\UserRoleDestroyAction;
use Modules\Authorization\Application\Actions\UserRole\UserRoleGetAction;
use Modules\Authorization\Application\Actions\UserRole\UserRoleStoreAction;
use Modules\Authorization\Application\Actions\UserRole\UserRoleSyncAction;
use Modules\Authorization\Application\DTOs\Input\UserRoles\UserRolesDestroyInputDto;
use Modules\Authorization\Application\DTOs\Input\UserRoles\UserRolesGetInputDto;
use Modules\Authorization\Application\DTOs\Input\UserRoles\UserRolesInputDto;
use Modules\Authorization\Domain\ValueObjects\RoleId;
use Modules\Authorization\Presentation\Http\Requests\UserRoles\BaseUserRoleRequest;
use Modules\Shared\Domain\ValueObjects\UserId;
use Modules\Shared\Presentation\Http\Controllers\BaseController;

class UserRoleController extends BaseController
{

    public function __construct(
        private readonly UserRoleGetAction $userRoleGetAction,
        private readonly UserRoleStoreAction $userRoleStoreAction,
        private readonly UserRoleDestroyAction $userRoleDestroyAction,
        private readonly UserRoleSyncAction $userRoleSyncAction,
    ) {
    }

    public function index(Request $request)
    {
        return $this->handle(function () use ($request) {

            $userWithRoles = $this->userRoleGetAction->execute(
                new UserRolesGetInputDto(
                    userId: new UserId($request->route('user')),
                )
            );
            return [
                'message' => 'User roles returned successfully.',
                'data' => new UserResource($userWithRoles),
            ];
        });
    }

    public function store(BaseUserRoleRequest $request)
    {
        return $this->handle(function () use ($request) {
            $userWithRoles = $this->userRoleStoreAction->execute(
                new UserRolesInputDto(
                    userId: new UserId($request->route('user')),
                    roles: $request->input('roles', []),
                )
            );
            return [
                'message' => 'User stored roles successfully.',
                'data' => new UserResource($userWithRoles),
            ];
        });
    }

    public function sync(BaseUserRoleRequest $request)
    {
        return $this->handle(function () use ($request) {

            $user = $this->userRoleSyncAction->execute(
                new UserRolesInputDto(
                    userId: new UserId($request->route('user')),
                    roles: $request->input('roles', []),
                ));
            return [
                'message' => 'User roles synced',
                'data' => new UserResource($user),
            ];
        });
    }


    public function destroy(Request $request)
    {
        return $this->handle(function () use ($request) {

            $this->userRoleDestroyAction->execute(
                new UserRolesDestroyInputDto(
                    userId: new UserId($request->route('user')),
                    roleId: new RoleId($request->route('role')),
                )
            );
            return [
                'message' => 'User removed role successfully.',
            ];
        });
    }
}
