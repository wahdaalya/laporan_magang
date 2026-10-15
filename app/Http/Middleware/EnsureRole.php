<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $userRole = strtoupper(trim((string) $user->role));

        $allowedRoles = [];
        foreach ($roles as $role) {
            foreach (explode(',', (string) $role) as $singleRole) {
                $trimmed = strtoupper(trim($singleRole));
                if ($trimmed !== '') {
                    $allowedRoles[] = $trimmed;
                }
            }
        }

        if (! in_array($userRole, $allowedRoles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        if ($userRole === 'MAHASISWA' && method_exists($user, 'isExpired') && $user->isExpired()) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            abort(403, 'Akun Anda sudah kadaluarsa (masa magang telah berakhir). Akses sistem telah dikunci.');
        }

        return $next($request);
    }
}
