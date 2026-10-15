<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PenilaianUploadController extends Controller
{
    public function store(Request $request, User $peserta): RedirectResponse
    {
        $pembimbing = $request->user();
        $mahasiswaBimbingan = $pembimbing->role === 'DOSEN'
            ? $pembimbing->mahasiswaBimbinganDosen()
            : $pembimbing->mahasiswaBimbinganMentor();

        abort_unless($mahasiswaBimbingan->whereKey($peserta->id)->exists(), 404);

        $data = $request->validate([
            'file' => ['required', 'file', 'mimes:pdf', 'mimetypes:application/pdf', 'extensions:pdf', 'max:10240'],
            'nilai_bimbingan' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai_laporan_akhir' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai_presentasi' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $path = $data['file']->store('penilaian', 'local');

        $peserta->penilaians()->create([
            'file' => $path,
            'uploaded_by' => $pembimbing->name,
            ...Arr::only($data, ['nilai_bimbingan', 'nilai_laporan_akhir', 'nilai_presentasi']),
        ]);

        return redirect()->route('pembimbing.peserta')
            ->with('status', 'Dokumen penilaian berhasil diunggah untuk '.$peserta->name.'.');
    }
}
