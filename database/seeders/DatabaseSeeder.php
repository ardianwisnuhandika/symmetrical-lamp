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
        // Create roles (idempotent)
        $superAdmin = Role::findOrCreate('super_admin', 'web');
        $adminDishub = Role::findOrCreate('admin_dishub', 'web');
        $verifikator = Role::findOrCreate('verifikator', 'web');

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
            Permission::findOrCreate($perm, 'web');
        }

        // Assign permissions to roles
        $superAdmin->syncPermissions(Permission::all());
        $adminDishub->syncPermissions(['view_dashboard', 'view_pju', 'create_pju', 'edit_pju']);
        $verifikator->syncPermissions(['view_dashboard', 'view_pju', 'verify_pju']);

        // Create users
        $superAdminUser = User::updateOrCreate([
            'email' => 'superadmin@luminousjepara.id',
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('password'),
        ]);
        $superAdminUser->syncRoles([$superAdmin]);

        $adminUser = User::updateOrCreate([
            'email' => 'admin@luminousjepara.id',
        ], [
            'name' => 'Admin Dishub',
            'password' => Hash::make('password'),
        ]);
        $adminUser->syncRoles([$adminDishub]);

        $verifikatorUser = User::updateOrCreate([
            'email' => 'verifikator@luminousjepara.id',
        ], [
            'name' => 'Verifikator',
            'password' => Hash::make('password'),
        ]);
        $verifikatorUser->syncRoles([$verifikator]);

        // Seed PJU data
        $this->call([
            CategorySeeder::class,
            PjuTypeSeeder::class,
            SystemSettingSeeder::class,
            PjuPointSeeder::class,
        ]);
    }
}
