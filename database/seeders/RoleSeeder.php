<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'description' => 'Administrator with full access'],
            ['name' => 'hr', 'description' => 'Human Resources, manages volunteers'],
            ['name' => 'copywriter', 'description' => 'User who can write blog posts'],
            ['name' => 'user', 'description' => 'Regular registered user'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}