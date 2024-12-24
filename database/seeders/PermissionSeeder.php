<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            ['name' => 'menu-post', 'category_name' => 'Post', 'method_name' => 'menu'],
            ['name' => 'list-post', 'category_name' => 'Post', 'method_name' => 'list'],
            ['name' => 'create-post', 'category_name' => 'Post', 'method_name' => 'create'],
            ['name' => 'show-post', 'category_name' => 'Post', 'method_name' => 'show'],
            ['name' => 'update-post', 'category_name' => 'Post', 'method_name' => 'update'],
            ['name' => 'delete-post', 'category_name' => 'Post', 'method_name' => 'delete'],
        ];

        foreach ($permissions as $permission) {
            $foundPermission  = DB::table('permissions')->whereName($permission['name'])->first();
            if (!$foundPermission) {
                DB::table('permissions')->insert([
                    'name' => $permission['name'],
                    'category_name' =>  $permission['category_name'],
                    'method_name' => $permission['method_name'],
                    'guard_name' => 'web',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }
    }
}
