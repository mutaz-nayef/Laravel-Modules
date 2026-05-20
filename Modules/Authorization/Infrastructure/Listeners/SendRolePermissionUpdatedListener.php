<?php

namespace Modules\Authorization\Infrastructure\Listeners;

//use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;
use Modules\Authorization\Domain\Events\RolePermissionUpdated;
use Modules\Authorization\Infrastructure\Events\PermissionAssignedForRole;
use Modules\Notifications\Application\Actions\StoreNotificationsAction;
use Modules\Notifications\Application\DTO\Input\StoreNotificationInputDto;

class SendRolePermissionUpdatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private readonly StoreNotificationsAction $storeNotificationsAction,
        private readonly UserRepositoryInterface $userRepository,
    ) {
        //
    }

    public function handle(RolePermissionUpdated $event): void
    {
        broadcast(new PermissionAssignedForRole(
            roleId: $event->roleId,
            permissionId: $event->permissionId,
        ));
        $admins = $this->userRepository->getAdmins();
        $users = $this->userRepository->findByRole($event->roleId);
        $recipientIds = collect(array_merge($admins, $users))
            ->unique()
            ->values();

        foreach ($recipientIds as $recipientId) {
            Mail::raw("Role: {$event->aggregateId()} Assigned new permissions: {$event->permissionId->value()}",
                function ($message) use ($event, $recipientId) {
                    $message->to($recipientId->email()->value())
                        ->subject('My Subject');
                });
        }
        $this->storeNotificationsAction->execute(
            new StoreNotificationInputDto(
                recipientIds: $recipientIds
                    ->map(fn($user) => $user->id())
                    ->toArray(),
                data: "Role: {$event->aggregateId()} Assigned new permissions: {$event->permissionId->value()}",
            )
        );
    }
}
