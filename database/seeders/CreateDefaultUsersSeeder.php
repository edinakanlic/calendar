<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateDefaultUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [];

        $users[] = [
            'name'     => 'administrator',
            'email'    => 'administrator@gmail.com',
            'password' => '12345678'
        ];

        $users[] = [
            'name'     => 'user',
            'email'    => 'user@gmail.com',
            'password' => '87654321'
        ];

        foreach($users as $user) {
            User::create($user);
        }
    }
}
