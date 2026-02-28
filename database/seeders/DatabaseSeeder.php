<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $superAdmin = Role::create(['name' => 'super_admin']);
        $adminDishub = Role::create(['name' => 'admin_dishub']);
        $verifikator = Role::create(['name' => 'verifikator']);

        // Create permissions
        $permissions = [
            'view_dashboard',
            'manage_users',
            'view_pju',
            'create_pju',
            'edit_pju',
            'delete_pju',
            'verify_pju',
            'view_logs',
        ];

        foreach ($permissions as $perm) {
            Permission::create(['name' => $perm]);
        }

        // Assign permissions to roles
        $superAdmin->givePermissionTo(Permission::all());
        $adminDishub->givePermissionTo(['view_dashboard', 'view_pju', 'create_pju', 'edit_pju']);
        $verifikator->givePermissionTo(['view_dashboard', 'view_pju', 'verify_pju']);

        // Create users
        $superAdminUser = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@luminousjepara.id',
            'password' => Hash::make('password'),
        ]);
        $superAdminUser->assignRole($superAdmin);

        $adminUser = User::create([
            'name' => 'Admin Dishub',
            'email' => 'admin@luminousjepara.id',
            'password' => Hash::make('password'),
        ]);
        $adminUser->assignRole($adminDishub);

        $verifikatorUser = User::create([
            'name' => 'Verifikator',
            'email' => 'verifikator@luminousjepara.id',
            'password' => Hash::make('password'),
        ]);
        $verifikatorUser->assignRole($verifikator);

        // Seed PJU data
        $this->call(PjuPointSeeder::class);
    }
}
