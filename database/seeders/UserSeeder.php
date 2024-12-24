<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!User::whereEmail('superadmin@mail.com')->first()) {
            $user = User::create([
                'name' => 'Superadmin',
                'email' => "superadmin@mail.com",
                'password' => Hash::make('P@ss1234'),
                'email_verified_at' => Carbon::now(),
                'remember_token' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            $user->assignRole('Superadmin');
        }

        if (!User::whereEmail('admin@mail.com')->first()) {
            $user = User::create([
                'name' => 'Admin',
                'email' => "admin@mail.com",
                'password' => Hash::make('P@ss1234'),
                'email_verified_at' => Carbon::now(),
                'remember_token' => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            $user->assignRole('Admin');
        }
    }
}
