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
use Illuminate\Database\Eloquent\Builder;
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
    public function index(Request $request): View|RedirectResponse
    {
        if (strtoupper((string) $request->user()?->role) === 'MAHASISWA') {
            return redirect()->route('mahasiswa.dashboard');
        }

        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'tahun' => ['nullable', 'integer', 'between:2000,2100'],
        ]);
        $search = trim($validated['q'] ?? '');
        $tahun = $validated['tahun'] ?? null;
        $mahasiswas = User::where('role', 'MAHASISWA')
            ->with(['kelompok.dosen', 'kelompok.pembimbing'])
            ->withCount('laporans')
            ->when($search !== '', function (Builder $query) use ($search): void {
                $pattern = '%'.$search.'%';

                $query->where(function (Builder $query) use ($pattern): void {
                    $query->where('name', 'like', $pattern)
                        ->orWhere('email', 'like', $pattern)
                        ->orWhere('nim_nip', 'like', $pattern)
                        ->orWhere('kampus', 'like', $pattern)
                        ->orWhere('fakultas', 'like', $pattern)
                        ->orWhere('prodi', 'like', $pattern)
                        ->orWhereHas('kelompok', fn (Builder $kelompok) => $kelompok->where('nama_kelompok', 'like', $pattern))
                        ->orWhereHas('kelompok.kampus', fn (Builder $kampus) => $kampus->where('nama_universitas', 'like', $pattern));
                });
            })
            ->when($tahun, fn (Builder $query) => $query->whereHas('kelompok', fn (Builder $kelompok) => $kelompok->whereYear('tanggal_mulai', $tahun)))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        if ($mahasiswas->isEmpty() && $mahasiswas->currentPage() > $mahasiswas->lastPage()) {
            return redirect()->route('mahasiswa.index', array_merge($request->query(), ['page' => $mahasiswas->lastPage()]));
        }

        $kelompoks = Kelompok::orderBy('nama_kelompok')->get(['id', 'nama_kelompok']);

        return view('mahasiswa.index', [
            'mahasiswas' => $mahasiswas,
            'q' => $search,
            'tahun' => $tahun,
            'daftarTahun' => Kelompok::daftarTahunMagang(),
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
        $user = $request->user()->load([
            'kelompok.kampus',
            'kelompok.departemen',
            'kelompok.dosen.user',
            'kelompok.pembimbing.user',
        ]);
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

        return back()->with('status', $request->boolean('from_kelompok')
            ? 'Anggota berhasil ditambahkan ke kelompok.'
            : 'Peserta berhasil ditambahkan.');
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

        return back()->with('status', 'Data peserta berhasil diperbarui.');
    }

    /**
     * Hapus data peserta.
     */
    public function destroy(User $mahasiswa): RedirectResponse
    {
        abort_unless($mahasiswa->role === 'MAHASISWA', 404);
        // Proteksi ekstra: SuperAdmin tidak pernah bisa dihapus melalui endpoint ini
        abort_if($mahasiswa->role === 'SUPERADMIN', 403, 'Akun Super Admin tidak dapat dihapus.');

        $filePaths = DB::transaction(function () use ($mahasiswa): array {
            $filePaths = collect([
                $mahasiswa->foto,
                $mahasiswa->laporanAkhir()->value('file_path'),
            ])
                ->merge($mahasiswa->laporans()->whereNotNull('foto')->pluck('foto'))
                ->merge($mahasiswa->penilaians()->pluck('file'))
                ->filter()
                ->unique()
                ->values()
                ->all();

            $mahasiswa->delete();

            return $filePaths;
        });

        Storage::disk('local')->delete($filePaths);

        return back()->with('status', 'Peserta berhasil dihapus.');
    }
}
