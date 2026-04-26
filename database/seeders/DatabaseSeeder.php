<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // UserModel::factory(10)->create();

        User::factory()->create([
            'name' => 'Mutaz Nayef',
            'email' => 'mutaz@example.com',
        ]);
    }
}
