<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!User::where('email', 'super-admin@mail.ru')->exists()) {
            $this->createSuperAdmin();
        }

        if (!User::where('email', 'admin@mail.ru')->exists()) {
            $this->createAdmin();
        }
    }

    private function createSuperAdmin()
    {
        /** @var User $superAdmin */
        $superAdmin = User::factory()->create([
            'first_name' => 'Super Admin',
            'last_name' => '',
            'email' => 'super-admin@mail.ru',
            'birth_date' => '1990-01-01',
            'password_hash' => Hash::make(config('app.admin.password')),
        ]);
        $superAdmin->assignRole(RoleEnum::SUPER_ADMIN->value);
    }

    private function createAdmin()
    {
        /** @var User $adminUser */
        $adminUser = User::factory()->create([
            'first_name' => 'Admin',
            'last_name' => '',
            'email' => 'admin@mail.ru',
            'birth_date' => '1990-01-01',
            'password_hash' => Hash::make(config('app.admin.password')),
        ]);
        $adminUser->assignRole(RoleEnum::ADMIN->value);
    }
}
