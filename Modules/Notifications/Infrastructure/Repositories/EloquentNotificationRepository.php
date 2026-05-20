<?php

namespace Modules\Notifications\Infrastructure\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\Notifications\Domain\Contracts\NotificationRepositoryInterface;
use Modules\Notifications\Domain\Entities\Notification;
use Modules\Notifications\Domain\ValueObjects\NotificationId;
use Modules\Notifications\Infrastructure\Models\NotificationModel;
use Modules\Shared\Domain\ValueObjects\UserId;

class EloquentNotificationRepository implements NotificationRepositoryInterface
{

    public function __construct(private readonly NotificationModel $model)
    {
    }

    public function findForUser(UserId $userId): ?array
    {
        $models = $this->model::whereHas('user',
            fn($query) => $query->where('id', $userId->value())
        )->get();

        if (!$models) {
            return null;
        }
        return $models->map(
            fn($m) => $this->toDomainEntity($m)
        )->all();
    }

    private function toDomainEntity(NotificationModel $model): Notification
    {
        return new Notification(
            id: new NotificationId($model->id),
            userId: new UserId($model->user_id),
            data: $model->data,
            readAt: $model->read_at
        );
    }

    public function findById(NotificationId $notificationId): ?Notification
    {
        // TODO: Implement findById() method.
    }

    public function save(Notification $notification): ?Notification
    {
        // TODO: Implement save() method.
    }

    public function delete(NotificationId $id): void
    {
        // TODO: Implement delete() method.
    }

    public function bulkInsert(array $notifications): void
    {
        $now = now();
        $records = array_map(fn(Notification $n) => [
            'user_id' => $n->userId()->value(),
            'data' => $n->data(),
            'read_at' => $n->readAt(),
            'created_at' => $now,
            'updated_at' => $now,
        ], $notifications);

        DB::table('notifications')->insert($records);
    }
}

