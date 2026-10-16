<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddAnggotaKelompokRequest;
use App\Http\Requests\StoreKelompokRequest;
use App\Http\Requests\UpdateKelompokRequest;
use App\Models\CalonAnggotaKelompok;
use App\Models\Departemen;
use App\Models\Dosen;
use App\Models\Kampus;
use App\Models\Kelompok;
use App\Models\Pembimbing;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KelompokController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'bulan' => ['nullable', 'integer', 'between:1,12'],
        ]);
        $search = trim($validated['q'] ?? '');
        $tahun = $validated['tahun'] ?? null;
        $bulan = $validated['bulan'] ?? null;
        $daftarBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $query = Kelompok::query()
            ->with([
                'kampus:id,nama_universitas',
                'dosen:id,nama',
                'pembimbing:id,nama',
                'departemen:id,nama_departemen',
                'mahasiswas:id,name,nim_nip,kelompok_id',
            ])
            ->withCount('mahasiswas');

        if ($search !== '') {
            $pattern = '%'.$search.'%';

            $query->where(function (Builder $q) use ($pattern): void {
                $q->where('nama_kelompok', 'like', $pattern)
                    ->orWhereHas('kampus', fn (Builder $kampus) => $kampus->where('nama_universitas', 'like', $pattern))
                    ->orWhereHas('dosen', fn (Builder $dosen) => $dosen->where('nama', 'like', $pattern))
                    ->orWhereHas('pembimbing', fn (Builder $pembimbing) => $pembimbing->where('nama', 'like', $pattern))
                    ->orWhereHas('departemen', fn (Builder $departemen) => $departemen->where('nama_departemen', 'like', $pattern));
            });
        }

        if ($tahun !== null) {
            $query->whereYear('tanggal_mulai', $tahun);
        }

        if ($bulan !== null) {
            $query->whereMonth('tanggal_mulai', $bulan);
        }

        $kelompoks = $query
            ->orderBy('nama_kelompok')
            ->paginate(10)
            ->withQueryString();

        if ($kelompoks->isEmpty() && $kelompoks->currentPage() > $kelompoks->lastPage()) {
            return redirect()->route('kelompok.index', array_merge($request->query(), ['page' => $kelompoks->lastPage()]));
        }

        return view('kelompok.index', [
            'bulan' => $bulan,
            'daftarBulan' => $daftarBulan,
            'kelompoks' => $kelompoks,
            'q' => $search,
            'tahun' => $tahun,
            'daftarTahun' => Kelompok::daftarTahunMagang(),
            'kampuses' => Kampus::orderBy('nama_universitas')->get(['id', 'nama_universitas']),
            'dosens' => Dosen::orderBy('nama')->get(['id', 'nama']),
            'pembimbings' => Pembimbing::orderBy('nama')->get(['id', 'nama']),
            'departemens' => Departemen::orderBy('nama_departemen')->get(['id', 'nama_departemen']),
            'mahasiswaBebas' => User::where('role', 'MAHASISWA')->whereNull('kelompok_id')->orderBy('name')->get(['id', 'name', 'nim_nip',
            ]),
        ]);
    }

    /**
     * Pencarian AJAX mahasiswa yang belum punya kelompok (buat live search di form Tambah Kelompok).
     */
    public function searchMahasiswa(Request $request): JsonResponse
    {
        $keyword = trim((string) $request->query('q', ''));

        $mahasiswas = User::where('role', 'MAHASISWA')
            ->whereNull('kelompok_id')
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                        ->orWhere('nim_nip', 'like', "%{$keyword}%");
                });
            })
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'nim_nip']);

        return response()->json($mahasiswas);
    }

    /**
     * Simpan data kelompok baru sekaligus anggotanya dan logo.
     */
    public function store(StoreKelompokRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $namaAnggota = $data['nama_anggota'];
        unset($data['nama_anggota']);

        $data['nama_kelompok'] = $this->generateKodeKelompok($data['tanggal_mulai']);

        DB::transaction(function () use ($data, $namaAnggota) {
            $kelompok = Kelompok::create($data);

            foreach ($namaAnggota as $nama) {
                CalonAnggotaKelompok::create([
                    'kelompok_id' => $kelompok->id,
                    'nama' => $nama,
                ]);
            }
        });

        return back()->with('status', 'Kelompok berhasil ditambahkan.');
    }

    /**
     * Buat kode kelompok otomatis, format: KEL-{tahun}-{urutan 3 digit}.
     */
    private function generateKodeKelompok(string $tanggalMulai): string
    {
        $tahun = Carbon::parse($tanggalMulai)->format('Y');

        $urutan = Kelompok::where('nama_kelompok', 'like', "KEL-{$tahun}-%")->count() + 1;

        return sprintf('KEL-%s-%03d', $tahun, $urutan);
    }

    /**
     * Perbarui data kelompok.
     */
    public function update(UpdateKelompokRequest $request, Kelompok $kelompok): RedirectResponse
    {
        $kelompok->update($request->validated());

        return back()->with('status', 'Kelompok berhasil diperbarui.');
    }

    /**
     * Hapus data kelompok dan file logo jika ada.
     */
    public function destroy(Kelompok $kelompok): RedirectResponse
    {
        if ($kelompok->logo) {
            Storage::disk('local')->delete($kelompok->logo);
        }

        $kelompok->delete();

        return redirect()->route('kelompok.index')->with(
            'status',
            'Kelompok berhasil dihapus.'
        );
    }

    /**
     * Tambahkan mahasiswa yang belum punya kelompok ke kelompok ini.
     */
    public function addAnggota(AddAnggotaKelompokRequest $request, Kelompok $kelompok): RedirectResponse
    {
        $mahasiswa = User::findOrFail($request->validated('mahasiswa_id'));

        DB::transaction(function () use ($mahasiswa, $kelompok) {
            $mahasiswa->update(['kelompok_id' => $kelompok->id]);

            CalonAnggotaKelompok::updateOrCreate(
                ['kelompok_id' => $kelompok->id, 'user_id' => $mahasiswa->id],
                ['nama' => $mahasiswa->name]
            );
        });

        return back()->with('status', 'Anggota berhasil ditambahkan.');
    }

    /**
     * Unduh detail kelompok magang sebagai file PDF.
     */
    public function exportPdf(Kelompok $kelompok): Response
    {
        $kelompok->load([
            'kampus',
            'dosen',
            'pembimbing',
            'departemen',
            'mahasiswas' => fn ($query) => $query->orderBy('name'),
        ]);

        $disk = Storage::disk('local');
        $logoPath = null;
        if ($kelompok->logo && $disk->exists($kelompok->logo)) {
            $logoPath = $disk->path($kelompok->logo);
        }

        if (! $logoPath || ! is_file($logoPath)) {
            // pakai logo default yang tersedia di public/images (urutan prioritas)
            $logoPath = collect(['logo_bandara.png', 'logo_bandara.jpg', 'logo-bintang-kualanamu.png'])
                ->map(fn (string $file) => public_path('images/'.$file))
                ->first(fn (string $path) => is_file($path));
        }

        return Pdf::loadView('kelompok.pdf', [
            'kelompok' => $kelompok,
            'logoPath' => $logoPath,
            'logoDataUri' => $kelompok->logo_data_uri,
            'dicetakPada' => now(),
        ])
            ->setPaper('a4', 'portrait')
            ->download('kelompok-'.Str::slug($kelompok->nama_kelompok).'.pdf');
    }
}
