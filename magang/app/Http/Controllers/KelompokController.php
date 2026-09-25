<?php

namespace App\Http\Controllers;


use App\Http\Requests\StoreKelompokRequest;
use App\Http\Requests\UpdateKelompokRequest;
use App\Models\Departemen;
use App\Models\Dosen;
use App\Models\Kampus;
use App\Models\Kelompok;
use App\Models\Pembimbing;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelompokController extends Controller
{
    public function index(): View
    {
        $kelompoks = Kelompok::with([
                'kampus:id,nama_universitas',
                'dosen:id,nama',
                'pembimbing:id,nama',
                'departemen:id,nama_departemen',
                'mahasiswas:id,name,nim_nip,kelompok_id',
            ])
            ->withCount('mahasiswas')
            ->orderBy('nama_kelompok')
            ->get();

        return view('kelompok.index', [
            'kelompoks' => $kelompoks,
            'kampuses' => Kampus::orderBy('nama_universitas')->get(['id', 'nama_universitas']),
            'dosens' => Dosen::orderBy('nama')->get(['id', 'nama']),
            'pembimbings' => Pembimbing::orderBy('nama')->get(['id', 'nama']),
            'departemens' => Departemen::orderBy('nama_departemen')->get(['id', 'nama_departemen']),
            'mahasiswaBebas' => User::where('role', 'MAHASISWA')->whereNull('kelompok_id')->orderBy('name')->get(['id', 'name', 'nim_nip']),
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
     * Simpan data kelompok baru sekaligus anggotanya.
     */
    public function store(StoreKelompokRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $mahasiswaIds = $data['mahasiswa_ids'];
        unset($data['mahasiswa_ids']);

        $data['nama_kelompok'] = $this->generateKodeKelompok($data['tanggal_mulai']);

        DB::transaction(function () use ($data, $mahasiswaIds) {
            $kelompok = Kelompok::create($data);

            User::whereIn('id', $mahasiswaIds)->update(['kelompok_id' => $kelompok->id]);
        });

        return redirect()->route('kelompok.index')->with('status', 'Kelompok berhasil ditambahkan.');
    }

    /**
     * Buat kode kelompok otomatis, format: KEL-{tahun}-{urutan 3 digit}.
     */
    private function generateKodeKelompok(string $tanggalMulai): string
    {
        $tahun = \Carbon\Carbon::parse($tanggalMulai)->format('Y');

        $urutan = Kelompok::where('nama_kelompok', 'like', "KEL-{$tahun}-%")->count() + 1;

        return sprintf('KEL-%s-%03d', $tahun, $urutan);
    }

    /**
     * Perbarui data kelompok.
     */
    public function update(UpdateKelompokRequest $request, Kelompok $kelompok): RedirectResponse
    {
        $kelompok->update($request->validated());

        return redirect()->route('kelompok.index')->with('status', 'Kelompok berhasil diperbarui.');
    }

    /**
     * Hapus data kelompok.
     */
    public function destroy(Kelompok $kelompok): RedirectResponse
    {
        $kelompok->delete();

        return redirect()->route('kelompok.index')->with('status', 'Kelompok berhasil dihapus.');
    }
}