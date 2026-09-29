<section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm" x-data="{ preview: @js($user->foto_url) }">
    <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <header class="border-b border-gray-100 px-5 py-4 sm:px-6">
            <h2 class="font-semibold text-gray-900">Edit Data Diri</h2>
            <p class="mt-1 text-sm text-gray-500">Perbarui nama, kontak, atau foto profil Anda.</p>
        </header>

        <div class="space-y-4 p-5 sm:p-6">
            <div class="flex items-center gap-4">
                <div class="relative h-20 w-20 shrink-0">
                    <img x-show="preview" x-bind:src="preview" alt="Foto profil {{ $user->name }}" class="h-20 w-20 rounded-full border border-gray-200 object-cover">
                    <div x-show="!preview" class="flex h-20 w-20 items-center justify-center rounded-full bg-blue-700 text-2xl font-semibold text-white">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </div>
                </div>
                <div class="min-w-0">
                    <label for="profile-foto" class="inline-flex cursor-pointer items-center gap-2 rounded-md border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Ubah Foto</label>
                    <input id="profile-foto" name="foto" type="file" accept="image/png,image/jpeg,image/webp" class="sr-only" x-on:change="if ($event.target.files[0]) { preview = URL.createObjectURL($event.target.files[0]) }">
                    <p class="mt-1 text-xs text-gray-500">JPG, PNG, WEBP · Maksimal 2 MB</p>
                    @error('foto')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="profile-name" class="mb-1 block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input id="profile-name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name" class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('name')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="profile-email" class="mb-1 block text-sm font-medium text-gray-700">Email</label>
                    <input id="profile-email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('email')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label for="profile-hp-wa" class="mb-1 block text-sm font-medium text-gray-700">Nomor WhatsApp / Telepon</label>
                    <input id="profile-hp-wa" name="hp_wa" type="tel" value="{{ old('hp_wa', $user->hp_wa) }}" autocomplete="tel" placeholder="08xxxxxxxxxx" class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('hp_wa')<p class="mt-1 text-sm text-rose-700">{{ $message }}</p>@enderror
                </div>
            </div>

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                    Email belum terverifikasi.
                    <button form="send-verification" class="ml-1 font-semibold underline">Kirim ulang tautan verifikasi</button>
                    @if (session('status') === 'verification-link-sent')
                        <span class="ml-2 text-emerald-700">Tautan verifikasi baru telah dikirim.</span>
                    @endif
                </div>
            @endif

            <div class="mt-6 flex justify-end border-t border-gray-100 pt-5">
                <button type="submit" class="inline-flex items-center gap-2 rounded-md bg-blue-700 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800">Simpan Perubahan</button>
            </div>
        </div>
    </form>
</section>
