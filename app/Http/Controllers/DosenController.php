<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Kampus;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DosenController extends Controller
{
    public function index(): View
    {
        $dosens = Dosen::withCount('kelompoks')
            ->orderBy('nama')
            ->get();

        $kampuses = Kampus::orderBy('nama_universitas')->get();

        return view('dosen.index', ['dosens' => $dosens, 'kampuses' => $kampuses]);
    }

    public function create(): View
    {
        return view('dosen.create');
    }

    /**
     * Simpan data dosen baru sekaligus akun login-nya (role DOSEN).
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
            'role' => 'DOSEN',
        ]);

        Dosen::create([...$data, 'user_id' => $user->id]);

        return redirect()->route('dosen.index')
            ->with('status', 'Data dosen berhasil ditambahkan.')
            ->with('password_baru', ['nama' => $data['nama'], 'email' => $data['email'], 'password' => $password]);
    }

    public function edit(Dosen $dosen): View
    {
        return view('dosen.edit', ['dosen' => $dosen]);
    }

    /**
     * Perbarui data dosen. Password hanya diubah jika diisi.
     */
    public function update(Request $request, Dosen $dosen): RedirectResponse
    {
        $data = $this->validated($request, $dosen);
        $password = $data['password'] ?? null;
        unset($data['password']);

        $dosen->update($data);

        if ($dosen->user_id) {
            $updateUser = [
                'name' => $data['nama'],
                'email' => $data['email'],
            ];

            if ($password) {
                $updateUser['password'] = Hash::make($password);
            }

            $dosen->user->update($updateUser);
        } elseif ($password) {
            $user = User::create([
                'name' => $data['nama'],
                'email' => $data['email'],
                'password' => Hash::make($password),
                'role' => 'DOSEN',
            ]);

            $dosen->update(['user_id' => $user->id]);
        }

        $redirect = redirect()->route('dosen.index')
            ->with('status', 'Data dosen berhasil diperbarui.');

        if ($password) {
            $redirect->with('password_baru', ['nama' => $data['nama'], 'email' => $data['email'], 'password' => $password]);
        }

        return $redirect;
    }

    public function destroy(Dosen $dosen): RedirectResponse
    {
        $dosen->delete();

        return redirect()->route('dosen.index')
            ->with('status', 'Data dosen berhasil dihapus.');
    }

    /**
     * Buat password acak baru untuk dosen ini, tampilkan sekali ke admin.
     */
    public function resetPassword(Dosen $dosen): RedirectResponse
    {
        if (! $dosen->user_id) {
            return redirect()->route('dosen.index')
                ->with('status', 'Dosen ini belum punya akun login. Isi email & password lewat form Ubah terlebih dahulu.');
        }

        $passwordBaru = Str::password(10, symbols: false);

        $dosen->user->update(['password' => Hash::make($passwordBaru)]);

        return redirect()->route('dosen.index')
            ->with('status', "Password untuk {$dosen->nama} berhasil di-reset.")
            ->with('password_baru', ['nama' => $dosen->nama, 'email' => $dosen->user->email, 'password' => $passwordBaru]);
    }

    private function validated(Request $request, ?Dosen $dosen = null): array
    {
        return $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nidn' => ['nullable', 'digits:10', Rule::unique('dosens', 'nidn')->ignore($dosen?->id)],
            'kampus' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($dosen?->user_id)],
            'password' => [$dosen ? 'nullable' : 'required', 'string', 'min:8'],
            'hp_wa' => ['nullable', 'regex:/^\+?[0-9]{8,15}$/'],
            'fakultas' => ['nullable', 'string', 'max:255'],
            'prodi' => ['nullable', 'string', 'max:255'],
        ], [
            'nama.required' => 'Nama dosen wajib diisi.',
            'nidn.digits' => 'NIDN harus terdiri dari 10 angka.',
            'nidn.unique' => 'NIDN ini sudah terdaftar.',
            'email.required' => 'Email wajib diisi, dipakai untuk akun login.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah dipakai akun lain.',
            'password.required' => 'Password wajib diisi untuk akun login dosen.',
            'password.min' => 'Password minimal 8 karakter.',
            'hp_wa.regex' => 'Nomor HP/WA hanya boleh angka (boleh diawali +), 8 sampai 15 digit.',
        ]);
    }
}