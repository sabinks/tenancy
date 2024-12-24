<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = ['Superadmin', 'Admin', 'Member'];

        foreach ($roles as $key => $role) {
            $foundRole = DB::table('roles')->whereName($role)->first();
            if (!$foundRole) {
                DB::table('roles')->create([
                    'name' => $role,
                ]);
            }
        }
    }
}
