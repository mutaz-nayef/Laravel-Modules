<?php

namespace Modules\Authorization\Infrastructure\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Authorization\Domain\ValueObjects\PermissionId;
use Modules\Authorization\Domain\ValueObjects\RoleId;

class PermissionAssignedForRole implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * Create a new event instance.
     */
    public function __construct(
        public readonly RoleId $roleId,
        public readonly PermissionId $permissionId
    ) {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return PrivateChannel[]
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('roles.'.$this->roleId->value()),
            new PrivateChannel('admins'),
        ];
    }

    public function broadcastWith(): void
    {
        logger()->info('Broadcast fired', [
            'role' => $this->roleId->value(),
            'permission' => $this->permissionId->value(),
        ]);
    }
}
