<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Pembimbing;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PenilaianUploadController extends Controller
{
    public function store(Request $request, User $peserta): RedirectResponse
    {
        $pembimbing = $request->user();
        $isDosen = $pembimbing->role === 'DOSEN';
        $profileId = $isDosen
            ? Dosen::query()->where('user_id', $pembimbing->id)->value('id')
            : Pembimbing::query()->where('user_id', $pembimbing->id)->value('id');
        $groupForeignKey = $isDosen ? 'dosen_id' : 'pembimbing_id';
        $directForeignKey = $isDosen ? 'dosen_id' : 'mentor_id';

        abort_unless(
            User::query()
                ->whereKey($peserta->id)
                ->where('role', 'MAHASISWA')
                ->where(function (Builder $query) use ($profileId, $groupForeignKey, $directForeignKey, $pembimbing): void {
                    $query->when($profileId, fn (Builder $query) => $query->whereHas('kelompok', fn (Builder $kelompokQuery) => $kelompokQuery->where($groupForeignKey, $profileId)))
                        ->orWhere($directForeignKey, $pembimbing->id);
                })
                ->exists(),
            404
        );

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
