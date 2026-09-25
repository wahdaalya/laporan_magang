<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartemenController extends Controller
{
    public function index(): View
    {
        $departemens = Departemen::withCount('kelompoks')
            ->orderBy('nama_departemen')
            ->get();
        return view('departemen.index', ['departemens' => $departemens]);
    }

        public function create(): View
    {
        return view('departemen.create', ['pilihan' => Departemen::PILIHAN]);
    }

    public function store(Request $request): RedirectResponse
    {
        Departemen::create($this->validated($request));

        return redirect()->route('departemen.index')
            ->with('status', 'Data departemen berhasil ditambahkan.');
    }

    public function edit(Departemen $departemen): View
    {
        return view('departemen.edit', ['departemen' => $departemen, 'pilihan' => Departemen::PILIHAN]);
    }

    public function update(Request $request, Departemen $departemen): RedirectResponse
    {
        $departemen->update($this->validated($request, $departemen));

        return redirect()->route('departemen.index')
            ->with('status', 'Data departemen berhasil diperbarui.');
    }

    public function destroy(Departemen $departemen): RedirectResponse
    {
        $departemen->delete();

        return redirect()->route('departemen.index')
            ->with('status', 'Data departemen berhasil dihapus.');
    }

        private function validated(Request $request, ?Departemen $departemen = null): array
    {
        return $request->validate([
            'nama_departemen' => [
                'required',
                Rule::in(Departemen::PILIHAN),
                Rule::unique('departemens', 'nama_departemen')->ignore($departemen?->id),
            ],
            'nama_pimpinan' => ['nullable', 'string', 'max:255'],
        ], [
            'nama_departemen.required' => 'Nama departemen wajib dipilih.',
            'nama_departemen.in' => 'Pilih departemen dari daftar yang tersedia.',
            'nama_departemen.unique' => 'Departemen ini sudah ada.',
        ]);
    }
}
