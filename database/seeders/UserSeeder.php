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
        $demoUsers = config('demo-users.users');
        $password = config('demo-users.system_password');

        foreach ($demoUsers as $user) {
            User::create([
                ...$user,
                'password' => $password
            ]);
        }
    }
}
