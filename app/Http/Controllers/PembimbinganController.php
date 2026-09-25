<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PembimbinganController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $kolomPembimbing = $user->role === 'DOSEN' ? 'dosen_id' : 'mentor_id';

        $mahasiswas = User::where('role', 'MAHASISWA')
            ->where($kolomPembimbing, $user->id)
            ->with('kelompok:id,nama_kelompok')
            ->withCount([
                'laporans as jumlah_laporan' => fn ($query) => $query,
                'laporans as jumlah_belum_diperiksa' => fn ($query) => $query->where('status', 'Belum Diperiksa'),
            ])
            ->orderBy('name')
            ->get();

        $laporanBelumDiperiksa = Laporan::where('status', 'Belum Diperiksa')
            ->whereHas('mahasiswa', fn ($query) => $query->where($kolomPembimbing, $user->id))
            ->with('mahasiswa:id,name')
            ->latest('tanggal')
            ->get();

        return view('pembimbingan.index', [
            'mahasiswas' => $mahasiswas,
            'laporanBelumDiperiksa' => $laporanBelumDiperiksa,
        ]);
    }
}
