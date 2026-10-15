<?php

namespace App\Http\Controllers;

use App\Models\Kampus;
use App\Models\Kelompok;
use App\Models\Laporan;
use App\Models\User;
use Illuminate\Contracts\View\View;

class SuperAdminController extends Controller
{
    public function index(): View
    {
        $perRole = User::query()
            ->selectRaw('role, count(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role');

        return view('superadmin.index', [
            'perRole' => $perRole,
            'totalUser' => $perRole->sum(),
            'totalKelompok' => Kelompok::count(),
            'totalKampus' => Kampus::count(),
            'totalLaporan' => Laporan::count(),
            'laporanMenunggu' => Laporan::where('status', 'Belum Diperiksa')->count(),
            'penggunaTerbaru' => User::latest()->limit(10)->get(['id', 'name', 'email', 'role', 'created_at']),
        ]);
    }
}