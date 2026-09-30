<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Edit Profil</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl space-y-6 sm:px-6 lg:px-8">
            @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">
                    {{ session('status') === 'password-updated' ? 'Kata sandi berhasil diperbarui.' : 'Perubahan profil berhasil disimpan.' }}
                </div>
            @endif

            <div class="space-y-6">
                @include('profile.partials.profile-account-form')

                <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
                    @include('profile.partials.update-password-form')
                </section>

                <details class="rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700">
                    <summary class="cursor-pointer font-medium">Pengelolaan akun</summary>
                    <div class="mt-4 border-t border-gray-100 pt-4">
                        @include('profile.partials.delete-user-form')
                    </div>
                </details>
            </div>
        </div>
    </div>
</x-app-layout>