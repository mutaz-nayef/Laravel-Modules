<?php

namespace Modules\Authorization\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Authorization\Application\DTOs\Output\RoleOutputDto;
use Modules\Authorization\Application\DTOs\PermissionDto;

class RoleResource extends JsonResource
{
    public function __construct(private readonly RoleOutputDto $output)
    {
        parent::__construct($output);
    }

    public function toArray(Request $request): array
    {


        return [
            'id' => $this->output->id->value(),
            'name' => $this->output->name,
            'display_name' => $this->output->display_name,
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
