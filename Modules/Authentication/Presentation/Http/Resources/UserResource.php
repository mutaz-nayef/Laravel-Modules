<?php

namespace Modules\Authentication\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Authorization\Application\DTOs\Output\RoleOutputDto;
use Modules\Authorization\Application\DTOs\Output\UserOutputDto;
use Modules\Authorization\Application\DTOs\PermissionDto;
use Modules\Authorization\Presentation\Http\Resources\PermissionResource;
use Modules\Authorization\Presentation\Http\Resources\RoleResource;

class UserResource extends JsonResource
{
    public function __construct(private readonly UserOutputDto $output)
    {
        parent::__construct($output);
    }

    public function toArray(Request $request): array
    {


        return [
            'id' => $this->output->id->value(),
            'name' => $this->output->name,
            'email' => $this->output->email,
            'roles' => RoleResource::collection(array_map(
                fn($role) => new RoleOutputDto(
                    id: $role->id(),
                    name: $role->name(),
                    display_name: $role->displayName(),
                    permissions: $role->permissions(),
                ),
                $this->output->roles
            )),
            'permissions' => PermissionResource::collection(array_map(
                fn($perm) => new PermissionDto(
                    id: $perm->id(),
                    name: $perm->name(),
                    group: $perm->group(),
                ),
                $this->output->permissions
            )),
        ];
    }
}
