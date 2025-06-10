<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleAdmin = Role::where('name', 'admin')->first();
        $roleHr = Role::where('name', 'hr')->first();
        $roleCopywriter = Role::where('name', 'copywriter')->first();
        $roleUser = Role::where('name', 'user')->first();

        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role_id' => $roleAdmin->id,
            ]
        );

        User::firstOrCreate(
            ['email' => 'hr@example.com'],
            [
                'name' => 'HR User',
                'username' => 'hrd',
                'password' => Hash::make('password'),
                'role_id' => $roleHr->id,
            ]
        );

        User::firstOrCreate(
            ['email' => 'copywriter@example.com'],
            [
                'name' => 'Copywriter User',
                'username' => 'penulis',
                'password' => Hash::make('password'),
                'role_id' => $roleCopywriter->id,
            ]
        );

        User::factory(20)->create([
            'role_id' => $roleUser->id,
        ]);
    }
}