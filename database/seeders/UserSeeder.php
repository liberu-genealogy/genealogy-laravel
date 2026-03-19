<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->withPersonalTeam()->create([
            "name"=> "Admin",
            "email"=> "joshuakisb@gmail.com",
            "password"=> Hash::make("password"),
            "email_verified_at"=> now(),
        ]);
        //     ['email' => 'admin@example.com'],
        //     [
        //         'name' => 'Admin',
        //         'password' => Hash::make('password'),
        //         'email_verified_at' => now(),
        //     ]
        // );

        

        // $user->teams()->attach($team->id);
        $user->assignRole('admin');
    }
}
