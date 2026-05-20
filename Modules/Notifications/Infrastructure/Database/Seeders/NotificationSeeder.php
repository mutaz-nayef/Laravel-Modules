<?php

namespace Modules\Notifications\Infrastructure\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Notifications\Infrastructure\Models\NotificationChannelModel;
use Modules\Notifications\Infrastructure\Models\NotificationTypesModel;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $channelsData = [
            ['name' => 'email', 'display_name' => 'Email'],
            ['name' => 'push', 'display_name' => 'Push']
        ];

        $channels = collect($channelsData)
            ->map(fn($c) => NotificationChannelModel::firstOrCreate(['name' => $c['name']], $c));

        $notificationTypesData = [
            ['name' => 'post_created', 'group' => 'posts'],
            ['name' => 'post_updated', 'group' => 'posts'],
            ['name' => 'post_deleted', 'group' => 'posts'],
            ['name' => 'permission_assigned', 'group' => 'permissions'],
            ['name' => 'permission_revoked', 'group' => 'permissions'],
            ['name' => 'role_assigned', 'group' => 'roles'],
            ['name' => 'role_revoked', 'group' => 'roles'],
        ];
        $notificationTypes = collect($notificationTypesData)
            ->map(fn($n) => NotificationTypesModel::firstOrCreate(['name' => $n['name']], $n));

    }
}
