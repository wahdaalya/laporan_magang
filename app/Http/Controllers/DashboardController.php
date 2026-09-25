<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->user()->role;

        return match ($role) {
            'ADMIN' => redirect()->route('admin.index'),
            'MAHASISWA' => redirect()->route('mahasiswa.dashboard'),
            'DOSEN', 'MENTOR' => redirect()->route('pembimbingan.index'),
            default => redirect('/'),
        };
    }
}
