<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DosenController extends Controller
{
    public function index(): View
    {
        $dosens = Dosen::withCount('kelompoks')
            ->orderBy('nama')
            ->get();

                return view('dosen.index', ['dosens' => $dosens]);
    }

    public function create(): View
    {
        return view('dosen.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Dosen::create($this->validated($request));

        return redirect()->route('dosen.index')
            ->with('status', 'Data dosen berhasil ditambahkan.');
    }

    public function edit(Dosen $dosen): View
    {
        return view('dosen.edit', ['dosen' => $dosen]);
    }

    public function update(Request $request, Dosen $dosen): RedirectResponse
    {
        $dosen->update($this->validated($request, $dosen));

        return redirect()->route('dosen.index')
            ->with('status', 'Data dosen berhasil diperbarui.');
    }

    public function destroy(Dosen $dosen): RedirectResponse
    {
        $dosen->delete();

        return redirect()->route('dosen.index')
            ->with('status', 'Data dosen berhasil dihapus.');
    }

    private function validated(Request $request, ?Dosen $dosen = null): array
    {
        return $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nidn' => ['nullable', 'digits:10', Rule::unique('dosens', 'nidn')->ignore($dosen?->id)],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('dosens', 'email')->ignore($dosen?->id)],
            'hp_wa' => ['nullable', 'regex:/^\+?[0-9]{8,15}$/'],
                        'fakultas' => ['nullable', 'string', 'max:255'],
            'prodi' => ['nullable', 'string', 'max:255'],
        ], [
            'nama.required' => 'Nama dosen wajib diisi.',
            'nidn.digits' => 'NIDN harus terdiri dari 10 angka.',
            'nidn.unique' => 'NIDN ini sudah terdaftar.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'hp_wa.regex' => 'Nomor HP/WA hanya boleh angka (boleh diawali +), 8 sampai 15 digit.',
        ]);
    }
}
