<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'abdullah@afaqcm.com'],
            [
                'name' => 'Abdullah Al-Saad',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'user_type' => 'admin',
            ]
        );

        if (class_exists(Role::class)) {
            $role = Role::firstOrCreate(['name' => 'super_admin']);
            if (!$user->hasRole($role->name)) {
                $user->assignRole($role);
            }
        }
    }
}
