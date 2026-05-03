<?php

namespace Modules\Authorization\Presentation\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Authorization\Application\Actions\Permission\PermissionDestroyAction;
use Modules\Authorization\Application\Actions\Permission\PermissionGetAction;
use Modules\Authorization\Application\Actions\Permission\PermissionShowAction;
use Modules\Authorization\Application\Actions\Permission\PermissionStoreAction;
use Modules\Authorization\Application\Actions\Permission\PermissionUpdateAction;
use Modules\Authorization\Application\DTOs\Input\Permission\BasePermissionInputDto;
use Modules\Authorization\Application\DTOs\Input\Permission\PermissionStoreInputDto;
use Modules\Authorization\Application\DTOs\Input\Permission\PermissionUpdateInputDto;
use Modules\Authorization\Domain\ValueObjects\PermissionId;
use Modules\Authorization\Presentation\Http\Requests\Permissions\StorePermissionRequest;
use Modules\Authorization\Presentation\Http\Requests\Permissions\UpdatePermissionRequest;
use Modules\Authorization\Presentation\Http\Resources\PermissionResource;
use Modules\Shared\Presentation\Http\Controllers\BaseController;

class PermissionController extends BaseController
{

    public function __construct(
        private readonly PermissionGetAction $permissionGetAction,
        private readonly PermissionStoreAction $permissionStoreAction,
        private readonly PermissionShowAction $permissionShowAction,
        private readonly PermissionUpdateAction $permissionUpdateAction,
        private readonly PermissionDestroyAction $permissionDestroyAction,
    ) {
    }

    public function index(Request $request)
    {
        return $this->handle(function () use ($request) {

            $permissions = $this->permissionGetAction->execute();

            return [
                'message' => 'Permissions returned',
                'data' => PermissionResource::collection($permissions)
            ];
        });
    }

    public function store(StorePermissionRequest $request)
    {
        return $this->handle(function () use ($request) {

            $permission = $this->permissionStoreAction->execute(
                new PermissionStoreInputDto(
                    name: $request->input('name'),
                    group: $request->input('group'),
                )
            );
            return [
                'message' => 'Permission Created.',
                'data' => new PermissionResource($permission)
            ];
        });
    }

    public function show($permission)
    {
        return $this->handle(function () use ($permission) {

            $permission = $this->permissionShowAction->execute(
                new BasePermissionInputDto(new PermissionId($permission))
            );

            return [
                'message' => 'Permission Returned.',
                'data' => new PermissionResource($permission),
            ];
        });
    }

    public function update(UpdatePermissionRequest $request)
    {
        return $this->handle(function () use ($request) {


            $permission = $this->permissionUpdateAction->execute(
                new PermissionUpdateInputDto(
                    permissionId: new PermissionId($request->route('permission')),
                    name: $request->input('name'),
                    group: $request->input('group'),
                )
            );

            return [
                'message' => 'PermissionModel updated.',
                'data' => new PermissionResource($permission)
            ];
        });
    }

    public function destroy($permission)
    {
        return $this->handle(function () use ($permission) {

            $permission = $this->permissionDestroyAction->execute(
                new BasePermissionInputDto(new PermissionId($permission))
            );

            return [
                'message' => 'Permission deleted successfully.',
            ];
        });
    }
}
