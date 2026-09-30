<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as ViewInstance;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        View::composer('layouts.app', function (ViewInstance $view): void {
            /** @var User|null $user */
            $user = Auth::user();

            if (! $user) {
                $view->with('profileSummary', null);

                return;
            }

            $roleLabel = match ($user->role) {
                'ADMIN' => 'Administrator',
                'MENTOR' => 'Pembimbing Lapangan',
                'DOSEN' => 'Dosen / Guru Pembimbing',
                'MAHASISWA' => 'Peserta Magang / PKL',
                default => 'Pengguna',
            };

            $view->with('profileSummary', [
                'name' => $user->name,
                'email' => $user->email,
                'photo' => $user->foto_url,
                'initial' => strtoupper(substr($user->name ?? 'U', 0, 1)),
                'role' => $roleLabel,
            ]);
        });
    }
}
