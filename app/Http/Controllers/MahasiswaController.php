<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMahasiswaRequest;
use App\Http\Requests\UpdateMahasiswaRequest;
use App\Models\CalonAnggotaKelompok;
use App\Models\Kampus;
use App\Models\Kelompok;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    /**
     * Daftar seluruh mahasiswa magang (halaman admin).
     */
    public function index(): View
    {
        $mahasiswas = User::where('role', 'MAHASISWA')
            ->with(['kelompok:id,nama_kelompok', 'dosen:id,name', 'mentor:id,name'])
            ->withCount('laporans')
            ->orderBy('name')
            ->get();

        $kelompoks = Kelompok::orderBy('nama_kelompok')->get(['id', 'nama_kelompok']);

        return view('mahasiswa.index', [
            'mahasiswas' => $mahasiswas,
            'kelompoks' => $kelompoks,
            'kampuses' => Kampus::orderBy('nama_universitas')->get(['nama_universitas', 'fakultas', 'prodi']),
            'calonAnggotas' => CalonAnggotaKelompok::with('kelompok:id,nama_kelompok')
                ->whereNull('user_id')
                ->orderBy('nama')
                ->get(),
        ]);
    }

    /**
     * Halaman utama mahasiswa: riwayat laporan dan info kelompok.
     */
    public function dashboard(Request $request): View
    {
        $user = $request->user()->load('kelompok');
        $today = today();
        $kelompok = $user->kelompok;
        $progressMagang = 0;
        $sisaHari = 0;
        $durasiTerpakai = 0;
        $durasiTotal = 0;

        if ($kelompok) {
            $tanggalMulai = $kelompok->tanggal_mulai;
            $tanggalSelesai = $kelompok->tanggal_selesai;
            $durasiTotal = max($tanggalMulai->diffInDays($tanggalSelesai) + 1, 1);
            $durasiTerpakai = $tanggalMulai->gt($today)
                ? 0
                : min($tanggalMulai->diffInDays($today) + 1, $durasiTotal);
            $progressMagang = (int) round(($durasiTerpakai / $durasiTotal) * 100);
            $sisaHari = max(0, (int) $today->diffInDays($tanggalSelesai, false));
        }

        $periodStart = $user->kelompok?->tanggal_mulai ?? $today->copy()->startOfWeek(Carbon::MONDAY);
        $periodEnd = $user->kelompok?->tanggal_selesai ?? $today->copy()->endOfWeek();

        if ($periodEnd->lt($periodStart)) {
            $periodStart = $today;
            $periodEnd = $today;
        }

        $weekOptions = [];
        $weekStart = $periodStart->copy()->startOfWeek(Carbon::MONDAY);
        $lastWeekStart = $periodEnd->copy()->startOfWeek(Carbon::MONDAY);

        for (; $weekStart->lte($lastWeekStart); $weekStart->addWeek()) {
            $friday = $weekStart->copy()->addDays(4);

            if ($periodStart->gt($friday)) {
                continue;
            }

            $rangeStart = $weekStart->lt($periodStart) ? $periodStart->copy() : $weekStart->copy();
            $rangeEnd = $friday->gt($periodEnd) ? $periodEnd->copy() : $friday;

            if ($rangeStart->gt($rangeEnd)) {
                continue;
            }

            $weekOptions[count($weekOptions) + 1] = [
                'start' => $rangeStart,
                'end' => $rangeEnd,
            ];
        }

        if ($weekOptions === []) {
            $weekOptions[1] = [
                'start' => $today->copy()->startOfWeek(Carbon::MONDAY),
                'end' => $today->copy()->startOfWeek(Carbon::MONDAY)->addDays(4),
            ];
        }

        $defaultWeek = array_key_first($weekOptions) ?? 1;
        $currentWeekStart = $today->copy()->startOfWeek(Carbon::MONDAY);

        foreach ($weekOptions as $weekNumber => $week) {
            if ($week['start']->isSameDay($currentWeekStart)) {
                $defaultWeek = $weekNumber;

                break;
            }
        }

        $selectedWeek = $request->integer('week', $defaultWeek);

        if (! array_key_exists($selectedWeek, $weekOptions)) {
            $selectedWeek = $defaultWeek;
        }

        $selectedRange = $weekOptions[$selectedWeek];
        $laporans = $user->laporans()
            ->whereBetween('tanggal', [$selectedRange['start']->toDateString(), $selectedRange['end']->toDateString()])
            ->latest('tanggal')
            ->get();

        return view('mahasiswa.dashboard', [
            'user' => $user,
            'laporans' => $laporans,
            'weekOptions' => $weekOptions,
            'selectedWeek' => $selectedWeek,
            'selectedRange' => $selectedRange,
            'progressMagang' => $progressMagang,
            'durasiTotal' => $durasiTotal,
            'sisaHari' => $sisaHari,
        ]);
    }

    public function laporanIndex(Request $request): View
    {
        return view('mahasiswa.laporan', [
            'laporans' => $request->user()->laporans()->latest('tanggal')->get(),
        ]);
    }

    /**
     * Simpan data peserta baru sekaligus akun login-nya.
     */
    public function store(StoreMahasiswaRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $calonAnggotaId = $data['calon_anggota_id'] ?? null;
        unset($data['calon_anggota_id']);

        unset($data['foto']);
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto-profil', 'local');
        }

        DB::transaction(function () use ($data, $calonAnggotaId) {
            $calon = $calonAnggotaId
                ? CalonAnggotaKelompok::whereNull('user_id')->find($calonAnggotaId)
                : null;

            if ($calon) {
                $data['kelompok_id'] = $calon->kelompok_id;
            }

            $user = User::create([
                ...$data,
                'role' => 'MAHASISWA',
                'password' => Hash::make($data['password']),
            ]);

            if ($calon) {
                $calon->update(['user_id' => $user->id]);
            }
        });

        if ($request->boolean('from_kelompok')) {
            return redirect()->route('kelompok.index')->with('status', 'Anggota berhasil ditambahkan ke kelompok.');
        }

        return redirect()->route('mahasiswa.index')->with('status', 'Peserta berhasil ditambahkan.');
    }

    /**
     * Perbarui data peserta. Password hanya diubah jika diisi.
     */
    public function update(UpdateMahasiswaRequest $request, User $mahasiswa): RedirectResponse
    {
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        unset($data['foto']);
        if ($request->hasFile('foto')) {
            if ($mahasiswa->foto) {
                Storage::disk('local')->delete($mahasiswa->foto);
            }
            $data['foto'] = $request->file('foto')->store('foto-profil', 'local');
        }

        $mahasiswa->update($data);

        return redirect()->route('mahasiswa.index')->with('status', 'Data peserta berhasil diperbarui.');
    }

    /**
     * Hapus data peserta.
     */
    public function destroy(User $mahasiswa): RedirectResponse
    {
        if ($mahasiswa->foto) {
            Storage::disk('local')->delete($mahasiswa->foto);
        }

        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')->with('status', 'Peserta berhasil dihapus.');
    }
}
