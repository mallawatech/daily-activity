<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //User::factory(10)->create();
        {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@email.com',
                'password' => bcrypt('admin@123'),
                'role' => 'admin',
            ]);
    
            User::create([
                'name' => 'User',
                'email' => 'user@email.com',
                'password' => bcrypt('user@123'),
                'role' => 'user',
            ]);
        }
    }
}
