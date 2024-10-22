<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        User::updateOrInsert(
            ['email' => 'admin@gmail.com'], 
            [
                'name' => 'Admin',
                'password' => bcrypt('ceyiva'),
                'role' => 'admin',
                'username' => 'beauty24', 
            ]
        );
        

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
