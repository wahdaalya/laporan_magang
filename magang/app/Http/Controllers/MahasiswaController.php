<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMahasiswaRequest;
use App\Http\Requests\UpdateMahasiswaRequest;
use App\Models\Kelompok;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        ]);
    }

    /**
     * Halaman utama mahasiswa: riwayat laporan dan info kelompok.
     */
        public function dashboard(Request $request): View
    {
        $user = $request->user()->load('kelompok');

        $laporans = $user->laporans()
            ->latest('tanggal')
            ->get();

        return view('mahasiswa.dashboard', [
            'user' => $user,
            'laporans' => $laporans,
        ]);
    }

    /**
     * Simpan data mahasiswa baru sekaligus akun login-nya.
     */
        public function store(StoreMahasiswaRequest $request): RedirectResponse
    {
        User::create([
            ...$request->validated(),
            'role' => 'MAHASISWA',
            'password' => Hash::make($request->validated('password')),
        ]);

        if ($request->boolean('from_kelompok')) {
            return redirect()->route('kelompok.index')->with('status', 'Anggota berhasil ditambahkan ke kelompok.');
        }

        return redirect()->route('mahasiswa.index')->with('status', 'Mahasiswa berhasil ditambahkan.');
    }

    /**
     * Perbarui data mahasiswa. Password hanya diubah jika diisi.
     */
    public function update(UpdateMahasiswaRequest $request, User $mahasiswa): RedirectResponse
    {
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $mahasiswa->update($data);

        return redirect()->route('mahasiswa.index')->with('status', 'Mahasiswa berhasil diperbarui.');
    }

    /**
     * Hapus data mahasiswa.
     */
    public function destroy(User $mahasiswa): RedirectResponse
    {
        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')->with('status', 'Mahasiswa berhasil dihapus.');
    }
}
