<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ubah Dosen') }}
        </h2>
    </x-slot>

    <div class="max-w-xl bg-white p-6 shadow-sm sm:rounded-lg">
        <form method="POST" action="{{ route('dosen.update', $dosen) }}">
            @csrf
            @method('PUT')
            @include('dosen._form')
        </form>
    </div>
</x-app-layout>