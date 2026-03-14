<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class VerificationPageAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_verifikator_can_access_verification_page(): void
    {
        $role = Role::findOrCreate('verifikator', 'web');
        $permission = Permission::findOrCreate('verify_pju', 'web');
        $role->givePermissionTo($permission);

        $user = User::factory()->create();
        $user->assignRole($role);

        $response = $this->actingAs($user)->get(route('admin.verification.index'));

        $response->assertOk();
    }

    public function test_admin_without_verify_permission_is_forbidden(): void
    {
        Role::findOrCreate('admin_dishub', 'web');
        Permission::findOrCreate('verify_pju', 'web');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.verification.index'));

        $response->assertForbidden();
    }
}
