<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\Pembimbing;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PembimbingController extends Controller
{
    public function index(): View
    {
        $pembimbings = Pembimbing::with('departemen:id,nama_departemen')
            ->withCount('kelompoks')
            ->orderBy('nama')
            ->get();

        return view('pembimbing.index', [
            'pembimbings' => $pembimbings,
            'departemens' => Departemen::orderBy('nama_departemen')->get(['id', 'nama_departemen']),
        ]);
    }

    /**
     * Simpan data pembimbing baru sekaligus akun login-nya (role MENTOR).
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $password = $data['password'];
        unset($data['password']);

        $user = User::create([
            'name' => $data['nama'],
            'email' => $data['email'],
            'password' => Hash::make($password),
            'role' => 'MENTOR',
        ]);

        Pembimbing::create([...$data, 'user_id' => $user->id]);

        return redirect()->route('pembimbing.index')
            ->with('status', 'Data pembimbing berhasil ditambahkan.');
    }

    /**
     * Perbarui data pembimbing. Password hanya diubah jika diisi.
     */
    public function update(Request $request, Pembimbing $pembimbing): RedirectResponse
    {
        $data = $this->validated($request, $pembimbing);
        $password = $data['password'] ?? null;
        unset($data['password']);

        $pembimbing->update($data);

        if ($pembimbing->user_id) {
            $updateUser = [
                'name' => $data['nama'],
                'email' => $data['email'],
            ];

            if ($password) {
                $updateUser['password'] = Hash::make($password);
            }

            $pembimbing->user->update($updateUser);
        } elseif ($password) {
            // Data lama yang belum punya akun login, dibuatkan sekarang.
            $user = User::create([
                'name' => $data['nama'],
                'email' => $data['email'],
                'password' => Hash::make($password),
                'role' => 'MENTOR',
            ]);

            $pembimbing->update(['user_id' => $user->id]);
        }

        return redirect()->route('pembimbing.index')
            ->with('status', 'Data pembimbing berhasil diperbarui.');
    }

    public function destroy(Pembimbing $pembimbing): RedirectResponse
    {
        $pembimbing->delete();

        return redirect()->route('pembimbing.index')
            ->with('status', 'Data pembimbing berhasil dihapus.');
    }

    /**
     * Buat password acak baru untuk pembimbing ini, tampilkan sekali ke admin.
     */
    public function resetPassword(Pembimbing $pembimbing): RedirectResponse
    {
        if (! $pembimbing->user_id) {
            return redirect()->route('pembimbing.index')
                ->with('status', 'Pembimbing ini belum punya akun login. Isi email & password lewat form Ubah terlebih dahulu.');
        }

        $passwordBaru = \Illuminate\Support\Str::password(10, symbols: false);

        $pembimbing->user->update(['password' => \Illuminate\Support\Facades\Hash::make($passwordBaru)]);

        return redirect()->route('pembimbing.index')
            ->with('status', "Password untuk {$pembimbing->nama} berhasil di-reset.")
            ->with('password_baru', ['nama' => $pembimbing->nama, 'email' => $pembimbing->user->email, 'password' => $passwordBaru]);
    }

    private function validated(Request $request, ?Pembimbing $pembimbing = null): array
    {
        return $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:50', Rule::unique('pembimbings', 'nip')->ignore($pembimbing?->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($pembimbing?->user_id)],
            'password' => [$pembimbing ? 'nullable' : 'required', 'string', 'min:8'],
            'hp_wa' => ['nullable', 'regex:/^\+?[0-9]{8,15}$/'],
            'departemen_id' => ['required', 'exists:departemens,id'],
        ], [
            'nama.required' => 'Nama pembimbing wajib diisi.',
            'nip.unique' => 'NIP ini sudah terdaftar.',
            'email.required' => 'Email wajib diisi, dipakai untuk akun login.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah dipakai akun lain.',
            'password.required' => 'Password wajib diisi untuk akun login pembimbing.',
            'password.min' => 'Password minimal 8 karakter.',
            'hp_wa.regex' => 'Nomor HP/WA hanya boleh angka (boleh diawali +), 8 sampai 15 digit.',
            'departemen_id.required' => 'Departemen wajib dipilih.',
            'departemen_id.exists' => 'Departemen yang dipilih tidak valid.',
        ]);
    }
}