<?php

namespace Modules\Authorization\Presentation\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Authorization\Application\Actions\RolePermission\RolePermissionDestroyAction;
use Modules\Authorization\Application\Actions\RolePermission\RolePermissionPatchAction;
use Modules\Authorization\Application\Actions\RolePermission\RolePermissionStoreAction;
use Modules\Authorization\Application\Actions\RolePermission\RolePermissionSyncAction;
use Modules\Authorization\Application\DTOs\Input\RolePermission\RoleDestroyPermissionInputDto;
use Modules\Authorization\Application\DTOs\Input\RolePermission\RolePermissionInputDto;
use Modules\Authorization\Application\DTOs\Input\RolePermission\RolePermissionPatchInputDto;
use Modules\Authorization\Domain\ValueObjects\PermissionId;
use Modules\Authorization\Domain\ValueObjects\RoleId;
use Modules\Authorization\Presentation\Http\Requests\RolePermissions\BaseRolePermissionRequest;
use Modules\Authorization\Presentation\Http\Requests\RolePermissions\UpdateRolePermissionRequest;
use Modules\Authorization\Presentation\Http\Resources\RoleResource;
use Modules\Shared\Presentation\Http\Controllers\BaseController;

class RolePermissionController extends BaseController
{
    public function __construct(
        private readonly RolePermissionStoreAction $rolePermissionStoreAction,
        private readonly RolePermissionDestroyAction $rolePermissionDestroyAction,
        private readonly RolePermissionSyncAction $rolePermissionSyncAction,
        private readonly RolePermissionPatchAction $rolePermissionPatchAction,
    ) {
    }

    public function index(Request $request)
    {

    }

    public function store(BaseRolePermissionRequest $request)
    {
        return $this->handle(function () use ($request) {
            $roleWithPermissions = $this->rolePermissionStoreAction->execute(
                new RolePermissionInputDto(
                    role: new RoleId($request->route('role')),
                    permissions: $request->input('permissions', []),
                )
            );
            return [
                'message' => 'Role stored permissions successfully.',

                'data' => new RoleResource($roleWithPermissions),
            ];
        });
    }

    public function sync(BaseRolePermissionRequest $request)
    {
        return $this->handle(function () use ($request) {

            $role = $this->rolePermissionSyncAction->execute(
                new RolePermissionInputDto(
                    role: new RoleId($request->route('role')),
                    permissions: $request->input('permissions', []),
                ));
            return [
                'message' => 'Role permission synced',
                'data' => new RoleResource($role),
            ];
        });
    }


    public function update(UpdateRolePermissionRequest $request)
    {
        return $this->handle(function () use ($request) {

            $role = $this->rolePermissionPatchAction->execute(
                new RolePermissionPatchInputDto(
                    roleId: new RoleId($request->route('role')),
                    add: $request->input('add', []),
                    remove: $request->input('remove', []),
                )
            );
            return [
                'message' => 'PermissionModel updated.',
                'data' => new RoleResource($role),
            ];

        });
    }

    public function destroy(Request $request)
    {
        return $this->handle(function () use ($request) {
            $this->rolePermissionDestroyAction->execute(
                new RoleDestroyPermissionInputDto(
                    role: new RoleId($request->route('role')),
                    permission: new PermissionId($request->route('permission')),
                )
            );
            return [
                'message' => 'Role deleted permission successfully.',
            ];
        });
    }

}
