<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_dashboard_request_redirects_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_dashboard_redirects_admin_to_admin_page(): void
    {
        $user = User::factory()->role('ADMIN')->create();

        $this->actingAs($user)->get('/dashboard')->assertRedirect(route('admin.index'));
    }

    public function test_dashboard_redirects_mahasiswa_to_mahasiswa_dashboard(): void
    {
        $user = User::factory()->role('MAHASISWA')->create();

        $this->actingAs($user)->get('/dashboard')->assertRedirect(route('mahasiswa.dashboard'));
    }

    #[DataProvider('pembimbingRoles')]
    public function test_dashboard_redirects_pembimbing_to_pembimbingan_page(string $role): void
    {
        $user = User::factory()->role($role)->create();

        $this->actingAs($user)->get('/dashboard')->assertRedirect(route('pembimbingan.index'));
    }

    public static function pembimbingRoles(): array
    {
        return [
            'DOSEN' => ['DOSEN'],
            'MENTOR' => ['MENTOR'],
        ];
    }

    public function test_admin_page_renders_for_admin(): void
    {
        $user = User::factory()->role('ADMIN')->create();

        $this->actingAs($user)->get('/admin')->assertOk()->assertSee('Dashboard Admin');
    }

    public function test_admin_page_forbids_mahasiswa_role(): void
    {
        $user = User::factory()->role('MAHASISWA')->create();

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_mahasiswa_dashboard_renders_for_mahasiswa(): void
    {
        $user = User::factory()->role('MAHASISWA')->create();

        $this->actingAs($user)->get('/mahasiswa/dashboard')->assertOk()->assertSee('Dashboard Mahasiswa');
    }

    public function test_mahasiswa_dashboard_forbids_admin_role(): void
    {
        $user = User::factory()->role('ADMIN')->create();

        $this->actingAs($user)->get('/mahasiswa/dashboard')->assertForbidden();
    }

    #[DataProvider('masterDataPages')]
    public function test_master_data_page_renders_for_admin(string $path, string $heading): void
    {
        $user = User::factory()->role('ADMIN')->create();

        $this->actingAs($user)->get($path)->assertOk()->assertSee($heading);
    }

    public static function masterDataPages(): array
    {
        return [
            'kampus' => ['/kampus', 'Data Kampus'],
            'dosen' => ['/dosen', 'Data Dosen'],
            'pembimbing' => ['/pembimbing', 'Data Pembimbing'],
            'departemen' => ['/departemen', 'Data Departemen'],
            'kelompok' => ['/kelompok', 'Data Kelompok'],
            'mahasiswa' => ['/mahasiswa', 'Data Mahasiswa'],
        ];
    }

    public function test_kampus_page_forbids_mahasiswa_role(): void
    {
        $user = User::factory()->role('MAHASISWA')->create();

        $this->actingAs($user)->get('/kampus')->assertForbidden();
    }

    public function test_mahasiswa_list_page_forbids_mahasiswa_role(): void
    {
        $user = User::factory()->role('MAHASISWA')->create();

        $this->actingAs($user)->get('/mahasiswa')->assertForbidden();
    }

    public function test_pembimbingan_page_renders_for_dosen(): void
    {
        $user = User::factory()->role('DOSEN')->create();

        $this->actingAs($user)->get('/pembimbingan')->assertOk()->assertSee('Dashboard Pembimbingan');
    }

    public function test_pembimbingan_page_forbids_mahasiswa_role(): void
    {
        $user = User::factory()->role('MAHASISWA')->create();

        $this->actingAs($user)->get('/pembimbingan')->assertForbidden();
    }
}
