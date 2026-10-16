<x-modal name="modal-reset-password" maxWidth="md">
    <form method="POST" x-bind:action="resetPasswordAction" class="p-6">
        @csrf
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                <i class="fa-solid fa-key text-lg"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ __('Reset Password Peserta') }}
                </h3>
                <p class="text-xs text-gray-500">
                    Atur ulang kata sandi login untuk akun peserta ini.
                </p>
            </div>
        </div>

        <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50/70 p-3 text-xs text-amber-800">
            Peserta: <strong x-text="resetNama" class="font-semibold"></strong>
            <template x-if="resetNim">
                <span>(NIM/NISN: <code x-text="resetNim" class="font-mono font-bold"></code>)</span>
            </template>
        </div>

        <div class="mt-4">
            <x-input-label for="modal_reset_password" value="{{ __('Password Baru (Opsional)') }}" />
            <x-text-input
                id="modal_reset_password"
                name="password"
                type="text"
                class="mt-1 block w-full text-sm"
                placeholder="Kosongkan untuk otomatis menggunakan NIM / default"
                autocomplete="new-password"
            />
            <p class="mt-1.5 text-xs text-gray-500">
                Bila dikosongkan, password akan otomatis diatur ke <strong>NIM/NISN</strong> peserta (atau <code class="rounded bg-gray-100 px-1 py-0.5">password123</code> jika NIM kosong).
            </p>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">
                {{ __('Batal') }}
            </x-secondary-button>

            <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-amber-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white shadow-sm transition hover:bg-amber-700 focus:bg-amber-700 active:bg-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                <i class="fa-solid fa-rotate-left"></i>
                {{ __('Reset Password') }}
            </button>
        </div>
    </form>
</x-modal>
