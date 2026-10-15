<x-modal name="confirm-delete-mahasiswa" maxWidth="md">
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900">Yakin ingin menghapus peserta ini?</h2>
        <p class="mt-1 text-sm text-gray-600">
            Peserta <strong x-text="deleteNama"></strong> dan seluruh laporan serta dokumen terkait akan dihapus permanen.
        </p>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">Batal</x-secondary-button>
            <form method="POST" x-bind:action="deleteAction">
                @csrf
                @method('DELETE')
                <x-danger-button type="submit">Ya, Hapus</x-danger-button>
            </form>
        </div>
    </div>
</x-modal>