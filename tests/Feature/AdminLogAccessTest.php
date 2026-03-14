<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminLogAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_access_audit_log_page(): void
    {
        $role = Role::findOrCreate('super_admin', 'web');
        $permission = Permission::findOrCreate('view_logs', 'web');
        $role->givePermissionTo($permission);

        $user = User::factory()->create();
        $user->assignRole($role);

        $response = $this->actingAs($user)->get(route('admin.logs.index'));

        $response->assertOk();
    }

    public function test_admin_without_view_logs_permission_gets_forbidden(): void
    {
        $role = Role::findOrCreate('admin_dishub', 'web');
        Permission::findOrCreate('view_logs', 'web');

        $user = User::factory()->create();
        $user->assignRole($role);

        $response = $this->actingAs($user)->get(route('admin.logs.index'));

        $response->assertForbidden();
    }
}
