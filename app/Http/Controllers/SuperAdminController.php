<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Kampus;
use App\Models\Kelompok;
use App\Models\Laporan;
use App\Models\Pembimbing;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
            'penggunaTerbaru' => User::latest()->limit(15)->get(['id', 'name', 'email', 'role', 'created_at']),
        ]);
    }

    /**
     * Hapus akun pengguna oleh Super Admin (Mahasiswa, Dosen, Mentor, Admin, Auditor).
     */
    public function destroyUser(User $user): RedirectResponse
    {
        if (auth()->user()?->role !== 'SUPERADMIN') {
            abort(403, 'Hanya Super Admin yang diizinkan menghapus akun.');
        }

        abort_if($user->role === 'SUPERADMIN', 403, 'Akun Super Admin utama tidak dapat dihapus.');

        DB::transaction(function () use ($user): void {
            if ($user->role === 'MAHASISWA') {
                $filePaths = collect([
                    $user->foto,
                    $user->laporanAkhir()->value('file_path'),
                ])
                    ->merge($user->laporans()->whereNotNull('foto')->pluck('foto'))
                    ->merge($user->penilaians()->pluck('file'))
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();
                Storage::disk('local')->delete($filePaths);
            } elseif ($user->role === 'DOSEN') {
                Dosen::where('user_id', $user->id)->orWhere('email', $user->email)->delete();
            } elseif ($user->role === 'MENTOR') {
                Pembimbing::where('user_id', $user->id)->orWhere('email', $user->email)->delete();
            }

            $user->delete();
        });

        return back()->with('status', "Akun {$user->name} ({$user->role}) berhasil dihapus permanen.");
    }
}
