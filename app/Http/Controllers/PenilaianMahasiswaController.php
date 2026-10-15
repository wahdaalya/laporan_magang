<?php

namespace App\Http\Controllers;

use App\Models\Penilaian;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PenilaianMahasiswaController extends Controller
{
    public function index(Request $request): View
    {
        return view('mahasiswa.penilaian');
    }

    public function download(Request $request, Penilaian $penilaian): StreamedResponse
    {
        abort(403, 'Nilai dan dokumen penilaian magang bersifat rahasia dan hanya dapat diakses oleh Dosen Pembimbing serta pihak kampus/sekolah.');
    }

    public function view(Request $request, Penilaian $penilaian): BinaryFileResponse
    {
        abort(403, 'Nilai dan dokumen penilaian magang bersifat rahasia dan hanya dapat diakses oleh Dosen Pembimbing serta pihak kampus/sekolah.');
    }
}
