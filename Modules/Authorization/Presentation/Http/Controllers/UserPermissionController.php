<?php

namespace Modules\Authorization\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Authentication\Presentation\Http\Resources\UserResource;
use Modules\Authorization\Application\Actions\UserPermission\UserPermissionBulkRemoveAction;
use Modules\Authorization\Application\Actions\UserPermission\UserPermissionDestroyAction;
use Modules\Authorization\Application\Actions\UserPermission\UserPermissionGetAction;
use Modules\Authorization\Application\Actions\UserPermission\UserPermissionStoreAction;
use Modules\Authorization\Application\Actions\UserPermission\UserPermissionSyncAction;
use Modules\Authorization\Application\DTOs\Input\UserPermission\UserDestroyPermissionInputDto;
use Modules\Authorization\Application\DTOs\Input\UserPermission\UserPermissionGetInputDto;
use Modules\Authorization\Application\DTOs\Input\UserPermission\UserPermissionInputDto;
use Modules\Authorization\Domain\ValueObjects\PermissionId;
use Modules\Authorization\Presentation\Http\Requests\UserPermissions\BaseUserPermissionsRequest;
use Modules\Shared\Domain\ValueObjects\UserId;
use Modules\Shared\Presentation\Http\Controllers\BaseController;

class UserPermissionController extends BaseController
{

    public function __construct(
        private readonly UserPermissionGetAction $userPermissionGetAction,
        private readonly UserPermissionStoreAction $userPermissionStoreAction,
        private readonly UserPermissionBulkRemoveAction $userPermissionBulkRemoveAction,
        private readonly UserPermissionDestroyAction $userPermissionDestroyAction,
        private readonly UserPermissionSyncAction $userPermissionSyncAction,
    ) {
    }

    public function index(Request $request)
    {
        return $this->handle(function () use ($request) {

            $userWithPermissions = $this->userPermissionGetAction->execute(
                new UserPermissionGetInputDto(
                    userId: new UserId($request->route('user')),
                )
            );
            return [
                'message' => 'User permissions returned successfully.',
                'data' => new UserResource($userWithPermissions),
            ];
        });
    }

    public function store(BaseUserPermissionsRequest $request)
    {
        return $this->handle(function () use ($request) {
            $user = $this->userPermissionStoreAction->execute(
                new UserPermissionInputDto(
                    userId: new UserId($request->route('user')),
                    permissions: $request->input('permissions', []),
                )
            );
            return [
                'message' => 'User stored permissions successfully.',
                'data' => new UserResource($user),
            ];
        });
    }

    public function sync(BaseUserPermissionsRequest $request)
    {
        return $this->handle(function () use ($request) {

            $user = $this->userPermissionSyncAction->execute(
                new UserPermissionInputDto(
                    userId: new UserId($request->route('user')),
                    permissions: $request->input('permissions', []),
                ));
            return [
                'message' => 'User permissions synced',
                'data' => new UserResource($user),
            ];
        });
    }


    public function bulkDelete(BaseUserPermissionsRequest $request)
    {
        return $this->handle(function () use ($request) {

            $output = $this->userPermissionBulkRemoveAction->execute(
                new UserPermissionInputDto(
                    userId: new UserId($request->route('user')),
                    permissions: $request->input('permissions', []),
                ));
            return [
                'message' => 'Permissions '.implode(', ', $request->input('permissions')).' removed successfully.',
                'data' => new UserResource($output),
            ];
        });
    }

    public function destroy(Request $request)
    {
        return $this->handle(function () use ($request) {

            $this->userPermissionDestroyAction->execute(
                new UserDestroyPermissionInputDto(
                    userId: new UserId($request->route('user')),
                    permissionId: new PermissionId($request->route('permission')),
                )
            );
            return [
                'message' => 'User removed permission successfully.',
            ];
        });
    }
}
