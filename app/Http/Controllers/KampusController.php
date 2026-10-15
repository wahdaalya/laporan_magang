<?php

namespace App\Http\Controllers;

use App\Models\Kampus;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KampusController extends Controller
{
    public function index(): View
    {
        $kampuses = Kampus::withCount('kelompoks')
            ->orderBy('nama_universitas')
            ->get();

        return view('kampus.index', ['kampuses' => $kampuses]);
    }

    public function store(Request $request): RedirectResponse
    {
        Kampus::create($this->validated($request));

        return redirect()->route('kampus.index')
            ->with('status', 'Data instansi berhasil ditambahkan.');
    }

    public function update(Request $request, Kampus $kampus): RedirectResponse
    {
        $kampus->update($this->validated($request, $kampus));

        return redirect()->route('kampus.index')
            ->with('status', 'Data instansi berhasil diperbarui.');
    }

    public function destroy(Kampus $kampus): RedirectResponse
    {
        $kampus->delete();

        return redirect()->route('kampus.index')
            ->with('status', 'Data instansi berhasil dihapus.');
    }

    /**
     * Validasi data kampus. Kombinasi Nama Universitas + Alamat + Fakultas + Prodi
     * harus unik — nama boleh sama asal salah satu kolom lain beda.
     */
    private function validated(Request $request, ?Kampus $kampus = null): array
    {
        return $request->validate([
            'nama_universitas' => [
                'required',
                'string',
                'max:255',
                Rule::unique('kampuses', 'nama_universitas')
                    ->where(function ($query) use ($request) {
                        foreach (['alamat', 'fakultas', 'prodi'] as $field) {
                            $value = $request->input($field);

                            $value === null || $value === ''
                                ? $query->whereNull($field)
                                : $query->where($field, $value);
                        }
                    })
                    ->ignore($kampus?->id),
            ],
            'alamat' => ['nullable', 'string', 'max:255'],
            'fakultas' => ['nullable', 'string', 'max:255'],
            'prodi' => ['nullable', 'string', 'max:255'],
        ], [
            'nama_universitas.required' => 'Nama instansi wajib diisi.',
            'nama_universitas.unique' => 'Data instansi dengan kombinasi Nama Instansi, Alamat, Fakultas, dan Program Studi yang sama sudah ada.',
        ]);
    }
}
