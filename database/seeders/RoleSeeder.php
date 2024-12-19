<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = ['Superadmin', 'Admin', 'Member'];

        foreach ($roles as $key => $role) {
            $foundRole = Role::whereName($role)->first();
            if (!$foundRole) {
                Role::create(['name' => $role]);
            }
        }
    }
}
