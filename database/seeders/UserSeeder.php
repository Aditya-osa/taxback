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
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@taxlegal.com',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'John Doe',
            'email' => 'john@taxlegal.com',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@taxlegal.com',
            'password' => bcrypt('password'),
        ]);
    }
}
