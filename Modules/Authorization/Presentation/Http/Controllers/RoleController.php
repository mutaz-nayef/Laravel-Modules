<?php

namespace Modules\Authorization\Presentation\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Authorization\Application\Actions\Role\RoleDestroyAction;
use Modules\Authorization\Application\Actions\Role\RoleGetAction;
use Modules\Authorization\Application\Actions\Role\RoleShowAction;
use Modules\Authorization\Application\Actions\Role\RoleStoreAction;
use Modules\Authorization\Application\Actions\Role\RoleUpdateAction;
use Modules\Authorization\Application\DTOs\Input\Role\BaseRoleInputDto;
use Modules\Authorization\Application\DTOs\Input\Role\RoleStoreInputDto;
use Modules\Authorization\Application\DTOs\Input\Role\RoleUpdateInputDto;
use Modules\Authorization\Domain\ValueObjects\RoleId;
use Modules\Authorization\Presentation\Http\Requests\Roles\StoreRoleRequest;
use Modules\Authorization\Presentation\Http\Requests\Roles\UpdateRoleRequest;
use Modules\Authorization\Presentation\Http\Resources\RoleResource;
use Modules\Shared\Presentation\Http\Controllers\BaseController;

class RoleController extends BaseController
{

    public function __construct(
        private readonly RoleGetAction $getRoleAction,
        private readonly RoleShowAction $showRoleAction,
        private readonly RoleStoreAction $storeRoleAction,
        private readonly RoleUpdateAction $updateRoleAction,
        private readonly RoleDestroyAction $destroyRoleAction,
    ) {
    }

    public function index(Request $request)
    {
        return $this->handle(function () use ($request) {

            $roles = $this->getRoleAction->execute();

            return [
                'message' => 'Roles returned',
                'data' => RoleResource::collection($roles)
            ];
        });
    }

    public function store(StoreRoleRequest $request)
    {
        return $this->handle(function () use ($request) {

            $role = $this->storeRoleAction->execute(
                new RoleStoreInputDto(
                    name: $request->input('name'),
                    display_name: $request->input('display_name'),
                )
            );
            return [
                'message' => 'Role Created.',
                'data' => new RoleResource($role)
            ];
        });
    }

    public function show($role)
    {
        return $this->handle(function () use ($role) {

            $role = $this->showRoleAction->execute(new BaseRoleInputDto(roleId: new RoleId($role)));

            return [
                'message' => 'RoleModel Returned.',
                'data' => new RoleResource($role),
            ];
        });
    }

    public function update(UpdateRoleRequest $request)
    {
        return $this->handle(function () use ($request) {

            $role = $this->updateRoleAction->execute(
                new RoleUpdateInputDto(
                    roleId: new RoleId($request->route('role')),
                    name: $request->input('name'),
                    display_name: $request->input('display_name'),
                )
            );

            return [
                'message' => 'RoleModel updated.',
                'data' => new RoleResource($role),
            ];
        });
    }

    public function destroy($role)
    {
        return $this->handle(function () use ($role) {

            $this->destroyRoleAction->execute(
                new BaseRoleInputDto(roleId: new RoleId($role))
            );

            return [
                'message' => 'Role deleted successfully.',
            ];
        });
    }
}
