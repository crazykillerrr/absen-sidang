<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementAndRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_access_user_management()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.users.index'));
        $response->assertStatus(200);
        $response->assertSee('Kelola Akun Pengguna');
    }

    public function test_hakim_cannot_access_user_management()
    {
        $hakim = User::factory()->create(['role' => 'hakim']);

        $response = $this->actingAs($hakim)->get(route('admin.users.index'));
        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('error');
    }

    public function test_super_admin_can_create_new_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Hakim Agung',
            'email' => 'hakim.agung@ptun.go.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'hakim',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'hakim.agung@ptun.go.id',
            'role' => 'hakim',
        ]);
    }

    public function test_jsp_pp_role_access_limits()
    {
        $jsp = User::factory()->create(['role' => 'jsp_pp']);

        // JSP/PP can access dashboard & daftar hadir hari ini
        $this->actingAs($jsp)->get(route('admin.dashboard'))->assertStatus(200);
        $this->actingAs($jsp)->get(route('admin.daftar-hadir-hari-ini'))->assertStatus(200);

        // JSP/PP cannot access perkara or laporan
        $this->actingAs($jsp)->get(route('admin.perkara.index'))->assertRedirect(route('admin.dashboard'));
        $this->actingAs($jsp)->get(route('admin.laporan.index'))->assertRedirect(route('admin.dashboard'));
    }

    public function test_ptsp_role_access_limits()
    {
        $ptsp = User::factory()->create(['role' => 'ptsp']);

        // PTSP can access dashboard, daftar hadir hari ini, and laporan
        $this->actingAs($ptsp)->get(route('admin.dashboard'))->assertStatus(200);
        $this->actingAs($ptsp)->get(route('admin.daftar-hadir-hari-ini'))->assertStatus(200);
        $this->actingAs($ptsp)->get(route('admin.laporan.index'))->assertStatus(200);

        // PTSP cannot access perkara
        $this->actingAs($ptsp)->get(route('admin.perkara.index'))->assertRedirect(route('admin.dashboard'));
    }
}
