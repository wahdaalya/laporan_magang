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
        return view('departemen.create', [
            'pilihan' => $this->pilihan(),
            'pilihanPimpinan' => $this->pilihanPimpinan(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Departemen::create($this->validated($request));

        return redirect()->route('departemen.index')
            ->with('status', 'Data departemen berhasil ditambahkan.');
    }

    public function edit(Departemen $departemen): View
    {
        return view('departemen.edit', [
            'departemen' => $departemen,
            'pilihan' => $this->pilihan(),
            'pilihanPimpinan' => $this->pilihanPimpinan(),
        ]);
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

    /**
     * Gabungan daftar departemen bawaan + nama yang sudah pernah dibuat, tanpa duplikat.
     *
     * @return array<int, string>
     */
    private function pilihan(): array
    {
        $sudahAda = Departemen::pluck('nama_departemen')->all();

        return collect(Departemen::PILIHAN)
            ->merge($sudahAda)
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    /**
     * Daftar nama pimpinan yang sudah pernah diisi sebelumnya, tanpa duplikat.
     *
     * @return array<int, string>
     */
    private function pilihanPimpinan(): array
    {
        return Departemen::whereNotNull('nama_pimpinan')
            ->distinct()
            ->orderBy('nama_pimpinan')
            ->pluck('nama_pimpinan')
            ->all();
    }

    private function validated(Request $request, ?Departemen $departemen = null): array
    {
        return $request->validate([
            'nama_departemen' => [
                'required',
                'string',
                'max:255',
                Rule::unique('departemens', 'nama_departemen')->ignore($departemen?->id),
            ],
            'nama_pimpinan' => ['nullable', 'string', 'max:255'],
        ], [
            'nama_departemen.required' => 'Nama departemen wajib diisi.',
            'nama_departemen.unique' => 'Departemen ini sudah ada.',
        ]);
    }
}