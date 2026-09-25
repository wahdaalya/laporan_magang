<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Departemen') }}
        </h2>
    </x-slot>

    <div class="max-w-2xl bg-white p-6 shadow-sm sm:rounded-lg">
        <form method="POST" action="{{ route('departemen.store') }}">
            @csrf
            @include('departemen._form')
        </form>
    </div>
</x-app-layout>