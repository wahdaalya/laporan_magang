<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->user()->role;

        return match ($role) {
            'SUPERADMIN' => redirect()->route('superadmin.index'),
            'ADMIN' => redirect()->route('admin.index'),
            'MAHASISWA' => redirect()->route('mahasiswa.dashboard'),
            'DOSEN' => redirect()->route('dosen.dashboard'),
            'MENTOR' => redirect()->route('pembimbingan.index'),
            default => redirect('/'),
        };
    }
}
