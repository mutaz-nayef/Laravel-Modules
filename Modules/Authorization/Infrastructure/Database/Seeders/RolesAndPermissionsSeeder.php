<?php

namespace Modules\Authorization\Infrastructure\Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Modules\Authorization\Infrastructure\Models\PermissionModel;
use Modules\Authorization\Infrastructure\Models\RoleModel;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- Permissions ---
        $permissionsData = [
            ['name' => 'roles:view', 'group' => 'roles'],
            ['name' => 'roles:create', 'group' => 'roles'],
            ['name' => 'roles:edit', 'group' => 'roles'],
            ['name' => 'roles:delete', 'group' => 'roles'],
            ['name' => 'permissions:view', 'group' => 'permissions'],
            ['name' => 'permissions:create', 'group' => 'permissions'],
            ['name' => 'permissions:edit', 'group' => 'permissions'],
            ['name' => 'permissions:delete', 'group' => 'permissions'],
            ['name' => 'users:view', 'group' => 'users'],
            ['name' => 'users:create', 'group' => 'users'],
            ['name' => 'users:edit', 'group' => 'users'],
            ['name' => 'users:delete', 'group' => 'users'],
            ['name' => 'home:view', 'group' => 'home'],
            ['name' => 'profiles:view', 'group' => 'home'],
            ['name' => 'posts:view', 'group' => 'posts'],
            ['name' => 'posts:create', 'group' => 'posts'],
            ['name' => 'posts:edit', 'group' => 'posts'],
            ['name' => 'posts:delete', 'group' => 'posts'],
        ];
        $permissions = collect($permissionsData)
            ->map(fn($p) => PermissionModel::firstOrCreate(['name' => $p['name']], $p));

        $byName = $permissions->keyBy('name');

        // --- ADMIN role: all permissions, no conditions ---
        $admin = RoleModel::firstOrCreate([
            'name' => 'admin',
            'display_name' => 'Administrator'
        ]);

        $admin->permissions()->sync(
            $permissions->mapWithKeys(fn($p) => [$p->id => ['conditions' => null]])->toArray()
        );

        // --- EDITOR role: can view all fields but can only write limited fields ---
        // --- Can only edit/delete posts that they OWN
        $editor = RoleModel::firstOrCreate([
            'name' => 'editor',
            'display_name' => 'Editor'
        ]);
        $editor->permissions()->sync([
            // View: can read most fields, but NOT internal_notes or cost
            $byName['posts:view']->id => [
                'conditions' => json_encode([
                    'readable_fields' => ['id', 'title', 'body', 'status', 'published_at', 'user_id'],
                ]),
            ],

            // Create: can only fill in title and body (not status, published_at, cost)
            $byName['posts:create']->id => [
                'conditions' => json_encode([
                    'writable_fields' => ['title', 'body'],
                ]),
            ],
            // Edit: owner only, can only write title and body
            $byName['posts:edit']->id => [
                'conditions' => json_encode([
                    'owner_only' => true,
                    'readable_fields' => ['id', 'title', 'body', 'status', 'published_at', 'user_id'],
                    'writable_fields' => ['title', 'body'],
                ]),
            ],
            // Delete: owner only, no field restrictions needed
            $byName['posts:delete']->id => [
                'conditions' => json_encode([
                    'owner_only' => true,
                ]),
            ],
        ]);


        // VIEWER — read-only, only published posts, minimal fields
        $viewer = RoleModel::firstOrCreate(['name' => 'viewer'], ['display_name' => 'Viewer']);
        $viewer->permissions()->sync([
            $byName['posts:view']->id => [
                'conditions' => json_encode([
                    'allowed_statuses' => ['published'],
                    'readable_fields' => ['id', 'title', 'body', 'published_at'],
                    // no writable_fields → viewer cannot write at all
                ]),
            ],
        ]);

        // --- User role: ---

        $normalUser = RoleModel::firstOrCreate([
            'name' => 'user',
            'display_name' => 'User'
        ]);

        $normalUser->permissions()->sync([
            $byName['posts:view']->id => ['conditions' => null],
            $byName['home:view']->id => ['conditions' => null],
            $byName['profiles:view']->id => ['conditions' => null]
        ]);
    }
}
