<?php

namespace Modules\Authentication\Infrastructure\Database\seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Authentication\Infrastructure\Models\UserModel;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // UserModel::factory(10)->create();

        UserModel::factory()->create([
            'name' => 'Mutaz Nayef',
            'email' => 'mutaz@example.com',
        ]);
    }
}
