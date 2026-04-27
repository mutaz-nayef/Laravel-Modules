<?php

namespace Modules\Authentication\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Authentication\Application\DTOs\Auth\Output\AuthOutputDto;

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
                'role' => $this->output->roleName,
            ],
            'permissions' => $this->output->permissions,
            'token' => [
                'access_token' => $this->output->token,
                'token_type' => $this->output->tokenType,
            ],
        ];
    }
}
