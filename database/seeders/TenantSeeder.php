<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Laravel\Passport\ClientRepository;

class TenantSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $client = new ClientRepository();

        $client->createPasswordGrantClient(null, 'Default password grant client', 'http://tenancy.test');
        $client->createPersonalAccessClient(null, 'Default personal access client', 'http://tenancy.test');
    }
}
