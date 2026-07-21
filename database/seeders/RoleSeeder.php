<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['slug' => 'user'], ['name' => 'User']);
        Role::firstOrCreate(['slug' => 'superadmin'], ['name' => 'Superadmin']);
    }
}
