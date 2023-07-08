<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::findByName(RoleEnum::ADMIN->value);
        $admin->givePermissionTo([
            PermissionEnum::USER_CREATE->value,
            PermissionEnum::USER_READ->value,
            PermissionEnum::USER_UPDATE->value,
        ]);
    }
}
