<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use App\Models\Laporan;
use App\Models\User;
use App\Support\StudentAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PrivateMediaController extends Controller
{
    public function profilePhoto(Request $request, User $user): BinaryFileResponse
    {
        abort_unless(StudentAccess::canView($request->user(), $user), 404);

        return $this->image($user->foto);
    }

    public function laporanPhoto(Request $request, Laporan $laporan): BinaryFileResponse
    {
        abort_unless(StudentAccess::canView($request->user(), $laporan->mahasiswa), 404);

        return $this->image($laporan->foto);
    }

    public function kelompokLogo(Request $request, Kelompok $kelompok): BinaryFileResponse
    {
        abort_if(! $kelompok->logo, 404);

        return $this->image($kelompok->logo);
    }

    private function image(?string $path): BinaryFileResponse
    {
        abort_if(! $path, 404);

        $disk = Storage::disk('local');
        abort_unless($disk->exists($path), 404);

        $mimeType = $disk->mimeType($path);
        abort_unless(in_array($mimeType, ['image/jpeg', 'image/png', 'image/webp'], true), 404);

        return response()->file($disk->path($path), [
            'Content-Type' => $mimeType,
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store, max-age=0',
        ]);
    }
}
