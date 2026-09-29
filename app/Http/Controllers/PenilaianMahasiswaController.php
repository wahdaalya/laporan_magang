<?php

namespace App\Http\Controllers;

use App\Models\Penilaian;
use Illuminate\Contracts\View\View;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PenilaianMahasiswaController extends Controller
{
    public function index(Request $request): View
    {
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        $penilaian = $request->user()->penilaians()
            ->whereRaw('LOWER(file) LIKE ?', ['%.pdf'])
            ->latest()
            ->get()
            ->first(fn (Penilaian $penilaian): bool => $disk->exists($penilaian->file)
                && $disk->mimeType($penilaian->file) === 'application/pdf');

        return view('mahasiswa.penilaian', ['penilaian' => $penilaian]);
    }

    public function download(Request $request, Penilaian $penilaian): StreamedResponse
    {
        abort_unless($penilaian->mahasiswa_id === $request->user()->id, 404);
        abort_unless(strtolower(pathinfo($penilaian->file, PATHINFO_EXTENSION)) === 'pdf', 404);

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        abort_unless($disk->exists($penilaian->file)
            && $disk->mimeType($penilaian->file) === 'application/pdf', 404);

        return $disk->download($penilaian->file, basename($penilaian->file), [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
