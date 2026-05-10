<?php

namespace Modules\Authentication\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Authentication\Application\DTOs\Auth\Output\AuthOutputDto;
use Modules\Authorization\Application\DTOs\Output\RoleOutputDto;
use Modules\Authorization\Presentation\Http\Resources\RoleResource;

class AuthResource extends JsonResource
{
    public function __construct(private readonly AuthOutputDto $output)
    {
        parent::__construct($output);
    }

    public function toArray(Request $request): array
    {
        return [
            'user' => [
                'id' => $this->output->userId,
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
            ],
            'permissions' => $this->output->permissions,
            'token' => [
                'access_token' => $this->output->accessToken,
                'refresh_token' => $this->output->refreshToken,
                'token_type' => $this->output->tokenType,
            ],
        ];
    }
}
