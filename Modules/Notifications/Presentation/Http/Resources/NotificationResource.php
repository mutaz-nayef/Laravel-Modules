<?php

namespace Modules\Notifications\Presentation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Notifications\Application\DTO\Output\NotificationOutputDto;

class NotificationResource extends JsonResource
{
    public function __construct(private readonly NotificationOutputDto $output)
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
            'notification_type' => $this->output->notificationTypeId()->value(),
            'user_id' => $this->output->userId()->value(),
            'data' => $this->output->data,
            'read_at' => $this->output->readAt
        ];
    }
}
