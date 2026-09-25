<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Contracts\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $statistik = [
            'mahasiswa' => User::where('role', 'MAHASISWA')->count(),
            'dosen' => User::where('role', 'DOSEN')->count(),
            'mentor' => User::where('role', 'MENTOR')->count(),
            'laporanBelumDiperiksa' => Laporan::where('status', 'Belum Diperiksa')->count(),
        ];

        $laporanTerbaru = Laporan::with('mahasiswa:id,name')
            ->latest('tanggal')
            ->limit(10)
            ->get();

        return view('admin.index', [
            'statistik' => $statistik,
            'laporanTerbaru' => $laporanTerbaru,
        ]);
    }
}
