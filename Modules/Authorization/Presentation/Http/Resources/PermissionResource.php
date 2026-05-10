<?php

namespace Modules\Authorization\Presentation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Authorization\Application\DTOs\Output\PermissionDto;

class PermissionResource extends JsonResource
{
    public function __construct(private readonly PermissionDto $output)
    {
        parent::__construct($output);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->output->id->value(),
            'name' => $this->output->name,
            'group' => $this->output->group,
        ];
    }
}
