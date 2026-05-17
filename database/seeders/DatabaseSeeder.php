<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Authentication\Infrastructure\Models\UserModel;
use Modules\Authorization\Infrastructure\Database\Seeders\RolesAndPermissionsSeeder;
use Modules\Authorization\Infrastructure\Models\RoleModel;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // UserModel::factory(10)->create();


        $this->call(RolesAndPermissionsSeeder::class);
        $adminRole = RoleModel::where('name', 'admin')->first();
        $user = UserModel::factory()->create([
            'name' => 'Mutaz Nayef',
            'email' => 'mutaz@example.com',
        ]);
        $user->roles()->attach($adminRole);

        $editorRole = RoleModel::where('name', 'editor')->first();
        $editor = UserModel::factory()->create([
            'name' => 'editor',
            'email' => 'editor@example.com',
        ]);
        $editor->roles()->attach($editorRole);

        $this->call(NotificationSeeder::class);
    }
}
