<?php

namespace App\Http\Controllers;

use App\Models\Kampus;
use Illuminate\Contracts\View\View;

class KampusController extends Controller
{
    public function index(): View
    {
        $kampuses = Kampus::withCount('kelompoks')
            ->orderBy('nama_universitas')
            ->get();

        return view('kampus.index', ['kampuses' => $kampuses]);
    }
}
