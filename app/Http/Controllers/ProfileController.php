<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Dosen;
use App\Models\Pembimbing;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan profil lengkap yang hanya-baca.
     */
    public function show(Request $request): View
    {
        $user = $request->user()->load([
            'kelompok.kampus',
            'kelompok.dosen',
            'kelompok.pembimbing',
            'kelompok.pembimbing.departemen',
            'kelompok.departemen',
            'laporanAkhir',
        ]);

        $dosenProfile = Dosen::query()
            ->with(['kelompoks.departemen', 'mahasiswas.kelompok.departemen', 'mahasiswas.kelompok.kampus'])
            ->where('user_id', $user->id)
            ->first();
        $pembimbingProfile = Pembimbing::query()
            ->with(['departemen', 'kelompoks.departemen', 'mahasiswas.kelompok.departemen', 'mahasiswas.kelompok.kampus'])
            ->where('user_id', $user->id)
            ->first();

        $extraData = [];

        if ($user->role === 'MAHASISWA') {
            $today = today();
            $kelompok = $user->kelompok;
            $progressMagang = 0;
            $sisaHari = 0;
            $durasiTotal = 0;
            $durasiTerpakai = 0;
            $statusMagang = 'Belum Terjadwal';

            if ($kelompok && $kelompok->tanggal_mulai && $kelompok->tanggal_selesai) {
                $mulai = $kelompok->tanggal_mulai;
                $selesai = $kelompok->tanggal_selesai;
                $durasiTotal = max($mulai->diffInDays($selesai) + 1, 1);
                $durasiTerpakai = $mulai->gt($today) ? 0 : min($mulai->diffInDays($today) + 1, $durasiTotal);
                $progressMagang = (int) round(($durasiTerpakai / $durasiTotal) * 100);
                $sisaHari = max(0, (int) $today->diffInDays($selesai, false));
                $statusMagang = $today->lt($mulai) ? 'Akan Dimulai' : ($today->gt($selesai) ? 'Selesai' : 'Aktif');
            }

            $totalLaporan = $user->laporans()->count();
            $laporanDisetujui = $user->laporans()->where('status', 'Disetujui')->count();
            $laporanPending = $user->laporans()->where('status', 'Belum Diperiksa')->count();
            $laporanRevisi = $user->laporans()->where('status', 'Revisi')->count();
            $penilaianTerbaru = $user->penilaians()->latest()->first();

            $extraData = [
                'statusMagang' => $statusMagang,
                'progressMagang' => $progressMagang,
                'sisaHari' => $sisaHari,
                'durasiTotal' => $durasiTotal,
                'durasiTerpakai' => $durasiTerpakai,
                'totalLaporan' => $totalLaporan,
                'laporanDisetujui' => $laporanDisetujui,
                'laporanPending' => $laporanPending,
                'laporanRevisi' => $laporanRevisi,
                'penilaian' => $penilaianTerbaru,
            ];
        } elseif ($user->role === 'DOSEN') {
            $pesertaBimbingan = $dosenProfile?->mahasiswas()
                ->with(['kelompok.departemen', 'kelompok.kampus'])
                ->orderBy('name')
                ->get() ?? collect();
            $kelompokBimbingan = ($dosenProfile?->kelompoks ?? collect())
                ->merge($pesertaBimbingan->pluck('kelompok')->filter())
                ->unique('id')
                ->values();

            $extraData = [
                'pesertaBimbingan' => $pesertaBimbingan,
                'totalBimbingan' => $pesertaBimbingan->count(),
                'kelompokBimbingan' => $kelompokBimbingan,
                'totalKelompokBimbingan' => $kelompokBimbingan->count(),
            ];
        } elseif ($user->role === 'MENTOR') {
            $pesertaBimbingan = $pembimbingProfile?->mahasiswas()
                ->with(['kelompok.departemen', 'kelompok.kampus'])
                ->orderBy('name')
                ->get() ?? collect();
            $kelompokBimbingan = ($pembimbingProfile?->kelompoks ?? collect())
                ->merge($pesertaBimbingan->pluck('kelompok')->filter())
                ->unique('id')
                ->values();

            $extraData = [
                'pesertaBimbingan' => $pesertaBimbingan,
                'totalBimbingan' => $pesertaBimbingan->count(),
                'kelompokBimbingan' => $kelompokBimbingan,
                'totalKelompokBimbingan' => $kelompokBimbingan->count(),
            ];
        }

        return view('profile.show', array_merge([
            'user' => $user,
            'dosenProfile' => $dosenProfile,
            'pembimbingProfile' => $pembimbingProfile,
        ], $extraData));
    }

    /**
     * Tampilkan form untuk mengubah data akun yang diizinkan.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validated();
        unset($data['foto']);
        
        // Nama akun ADMIN tidak pernah ikut di-update.
        if ($user->role === 'ADMIN') {
            unset($data['name']);
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($request->hasFile('foto')) {
            if ($user->foto) {
                Storage::disk('local')->delete($user->foto);
            }
            $user->foto = $request->file('foto')->store('foto-profil', 'local');
        }

        $user->save();

        return Redirect::route('profile.show')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        if ($user->foto) {
            Storage::disk('local')->delete($user->foto);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
