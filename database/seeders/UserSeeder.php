<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@taxlegal.com'],
            ['name' => 'Admin User', 'password' => bcrypt('password')]
        );

        User::updateOrCreate(
            ['email' => 'john@taxlegal.com'],
            ['name' => 'John Doe', 'password' => bcrypt('password')]
        );

        User::updateOrCreate(
            ['email' => 'jane@taxlegal.com'],
            ['name' => 'Jane Smith', 'password' => bcrypt('password')]
        );
    }
}
