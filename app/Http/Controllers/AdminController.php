<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

    /** Admin masuk sebagai mahasiswa. */
    public function impersonate(Request $request, User $user): RedirectResponse
    {
        abort_unless($request->user()->role === 'ADMIN', 403);
        abort_unless($user->role === 'MAHASISWA', 403, 'Hanya akun mahasiswa yang bisa dimasuki.');
        abort_if(session()->has('admin_impersonator_id'), 403, 'Anda sedang dalam mode impersonate.');

        session(['admin_impersonator_id' => auth()->id()]); // simpan ID admin asli, sebelum login
        Auth::login($user);

        Log::info('Impersonate dimulai', [
            'admin_id' => session('admin_impersonator_id'),
            'mahasiswa_id' => $user->id,
        ]);

        return redirect()->route('mahasiswa.dashboard');
    }

    /** Kembali ke akun admin asli. */
    public function leaveImpersonate(Request $request): RedirectResponse
    {
        $adminId = $request->session()->pull('admin_impersonator_id');
        abort_unless($adminId, 403);

        $originalAdmin = User::where('role', 'ADMIN')->findOrFail($adminId);

        Auth::login($originalAdmin);

        return redirect()->route('mahasiswa.index');
    }
}