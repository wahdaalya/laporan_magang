<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKampusRequest;
use App\Http\Requests\UpdateKampusRequest;
use App\Models\Kampus;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class KampusController extends Controller
{
    public function index(): View
    {
        $kampuses = Kampus::withCount('kelompoks')
            ->orderBy('nama_universitas')
            ->get();

        return view('kampus.index', ['kampuses' => $kampuses]);
    }

    /**
     * Simpan data kampus baru.
     */
    public function store(StoreKampusRequest $request): RedirectResponse
    {
        Kampus::create($request->validated());

        return redirect()->route('kampus.index')->with('status', 'Kampus berhasil ditambahkan.');
    }

    /**
     * Perbarui data kampus.
     */
    public function update(UpdateKampusRequest $request, Kampus $kampus): RedirectResponse
    {
        $kampus->update($request->validated());

        return redirect()->route('kampus.index')->with('status', 'Kampus berhasil diperbarui.');
    }

    /**
     * Hapus data kampus.
     */
    public function destroy(Kampus $kampus): RedirectResponse
    {
        $kampus->delete();

        return redirect()->route('kampus.index')->with('status', 'Kampus berhasil dihapus.');
    }
}