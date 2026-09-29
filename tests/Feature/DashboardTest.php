<?php

namespace Tests\Feature;

use App\Models\Kelompok;
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
    public function test_dashboard_redirects_supervisor_to_the_role_dashboard(string $role, string $route): void
    {
        $user = User::factory()->role($role)->create();

        $this->actingAs($user)->get('/dashboard')->assertRedirect(route($route));
    }

    public static function pembimbingRoles(): array
    {
        return [
            'DOSEN' => ['DOSEN', 'dosen.dashboard'],
            'MENTOR' => ['MENTOR', 'pembimbingan.index'],
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

    public function test_participant_dashboard_renders_for_mahasiswa_role(): void
    {
        $user = User::factory()->role('MAHASISWA')->create();

        $this->actingAs($user)->get('/mahasiswa/dashboard')
            ->assertOk()
            ->assertSee('Dashboard Peserta Magang / PKL')
            ->assertSee('Lihat Semua Laporan');
    }

    public function test_dashboard_welcome_banner_shows_participant_name_and_group_duration_progress(): void
    {
        $this->travelTo('2026-09-29 12:00:00');
        $kelompok = Kelompok::create([
            'nama_kelompok' => 'KEL-2026-002',
            'tanggal_mulai' => '2026-09-25',
            'tanggal_selesai' => '2026-09-30',
        ]);
        $user = User::factory()->role('MAHASISWA')->create([
            'name' => 'Wahda Alya',
            'kelompok_id' => $kelompok->id,
        ]);

        $this->actingAs($user)->get('/mahasiswa/dashboard')
            ->assertOk()
            ->assertSee('Selamat Datang Kembali, Wahda Alya!')
            ->assertSee('Tetap semangat mencatat kegiatan harianmu hari ini!')
            ->assertSee('Sisa 1 Hari Lagi')
            ->assertSee('Masa Magang / PKL Berakhir')
            ->assertSee('83%')
            ->assertSee('Dari total durasi 6 hari')
            ->assertDontSee('KEL-2026-002')
            ->assertDontSee('Status Periode Selesai')
            ->assertDontSee('Hari 12 dari 12');
    }

    public function test_weekly_dashboard_shows_only_weekdays_from_the_selected_group_week(): void
    {
        $user = User::factory()->role('MAHASISWA')->create();
        $kelompok = Kelompok::create([
            'nama_kelompok' => 'PKL-2026-01',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-09-30',
        ]);
        $user->update(['kelompok_id' => $kelompok->id]);
        $user->laporans()->createMany([
            ['tanggal' => '2026-09-08', 'kegiatan' => 'Laporan minggu terpilih'],
            ['tanggal' => '2026-09-15', 'kegiatan' => 'Laporan minggu berjalan'],
            ['tanggal' => '2026-09-12', 'kegiatan' => 'Laporan akhir pekan'],
        ]);

        $this->actingAs($user)->get('/mahasiswa/dashboard?week=2')
            ->assertOk()
            ->assertSee('Minggu 2')
            ->assertSee('Laporan minggu terpilih')
            ->assertDontSee('Laporan minggu berjalan')
            ->assertDontSee('Laporan akhir pekan');
    }

    public function test_weekly_dashboard_does_not_include_a_weekend_group_start_date(): void
    {
        $user = User::factory()->role('MAHASISWA')->create();
        $kelompok = Kelompok::create([
            'nama_kelompok' => 'PKL-2026-02',
            'tanggal_mulai' => '2026-09-06',
            'tanggal_selesai' => '2026-09-11',
        ]);
        $user->update(['kelompok_id' => $kelompok->id]);
        $user->laporans()->createMany([
            ['tanggal' => '2026-09-06', 'kegiatan' => 'Laporan hari Minggu'],
            ['tanggal' => '2026-09-07', 'kegiatan' => 'Laporan hari Senin'],
        ]);

        $this->actingAs($user)->get('/mahasiswa/dashboard?week=1')
            ->assertOk()
            ->assertSee('Laporan hari Senin')
            ->assertDontSee('Laporan hari Minggu');
    }

    public function test_all_reports_page_only_shows_the_authenticated_participants_reports(): void
    {
        $user = User::factory()->role('MAHASISWA')->create();
        $otherUser = User::factory()->role('MAHASISWA')->create();
        $user->laporans()->create(['tanggal' => '2026-09-08', 'kegiatan' => 'Laporan milik saya']);
        $otherUser->laporans()->create(['tanggal' => '2026-09-08', 'kegiatan' => 'Laporan peserta lain']);

        $this->actingAs($user)->get(route('mahasiswa.laporan.index'))
            ->assertOk()
            ->assertSee('Laporan milik saya')
            ->assertDontSee('Laporan peserta lain');
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
            'mahasiswa' => ['/mahasiswa', 'Data Peserta Magang / PKL'],
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

        $this->actingAs($user)->get('/pembimbingan')
            ->assertOk()
            ->assertSee('Dashboard Pembimbing Magang / PKL')
            ->assertSee('Total Peserta Bimbingan');
    }

    public function test_pembimbingan_page_forbids_mahasiswa_role(): void
    {
        $user = User::factory()->role('MAHASISWA')->create();

        $this->actingAs($user)->get('/pembimbingan')->assertForbidden();
    }
}
