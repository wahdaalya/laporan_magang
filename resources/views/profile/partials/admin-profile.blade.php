<div class="space-y-6">
    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Card 1: Identitas Administrator --}}
        <section class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm transition hover:shadow-md">
            <header class="flex items-center justify-between border-b border-gray-100 bg-gradient-to-r from-slate-50 to-blue-50/50 px-5 py-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-900 text-white shadow-sm">
                        <i class="fas fa-shield-alt text-sm"></i>
                    </span>
                    <div>
                        <h3 class="font-bold text-gray-900">Identitas Administrator</h3>
                        <p class="text-xs text-gray-500">Super Administrator Sistem</p>
                    </div>
                </div>
                <span class="inline-flex items-center rounded-full border border-blue-200 bg-blue-50 px-2.5 py-0.5 text-xs font-semibold text-blue-700">
                    Administrator
                </span>
            </header>

            <div class="p-5 sm:p-6">
                <dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Hak Akses Sistem</dt>
                        <dd class="mt-1 text-sm font-bold text-gray-900">Akses Penuh (Full Control)</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Instansi Penyelenggara</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">PT Angkasa Pura Aviasi</dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Email Utama</dt>
                        <dd class="mt-1 break-all text-sm font-medium text-gray-800">
                            @if ($user->email)
                                <a href="mailto:{{ $user->email }}" class="hover:text-blue-700 hover:underline">{{ $user->email }}</a>
                            @else
                                -
                            @endif
                        </dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">No. WhatsApp / Kontak</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-800">
                            @php($whatsappUrl = \App\Support\ContactLink::whatsappUrl($user->hp_wa))
                            @if ($whatsappUrl)
                                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="hover:text-emerald-700 hover:underline">{{ $user->hp_wa }}</a>
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </section>

        {{-- Card 2: Pengelolaan & Akses Cepat --}}
        <section class="overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm transition hover:shadow-md">
            <header class="flex items-center justify-between border-b border-gray-100 bg-gradient-to-r from-slate-50 to-indigo-50/50 px-5 py-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm">
                        <i class="fas fa-cogs text-sm"></i>
                    </span>
                    <div>
                        <h3 class="font-bold text-gray-900">Pusat Manajemen Master Data</h3>
                        <p class="text-xs text-gray-500">Kelola operasional magang lintas unit</p>
                    </div>
                </div>
            </header>

            <div class="p-5 sm:p-6 space-y-4">
                <p class="text-xs text-gray-600 leading-relaxed">
                    Sebagai administrator, Anda memiliki wewenang untuk menambah, mengubah, serta memverifikasi data instansi, pembimbing sekolah/kampus, pembimbing lapangan, departemen, kelompok magang/PKL, dan akun peserta.
                </p>

                <div class="pt-2">
                    <a href="{{ route('admin.index') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-3 text-sm font-bold text-white hover:bg-slate-800 shadow-sm transition">
                        <i class="fas fa-tachometer-alt"></i> Buka Dashboard Admin
                    </a>
                </div>
            </div>
        </section>
    </div>
</div>
