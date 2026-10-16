@props([
    'name',
    'id' => null,
    'value' => '',
    'options' => [],
    'placeholder' => 'Ketik atau pilih...',
    'required' => false,
    'model' => null,
])

@php
    $id = $id ?? $name;
    $optionsList = collect($options)->filter()->unique()->values()->all();
@endphp

<div
    class="relative w-full max-w-full min-w-0"
    style="width: 100%; max-width: 100%; box-sizing: border-box;"
    x-data="{
        open: false,
        query: {{ $model ? $model : json_encode((string) $value) }},
        options: {{ json_encode($optionsList) }},
        init() {
            @if ($model)
            this.$watch('{{ $model }}', val => { this.query = val ?? ''; });
            @endif
        },
        get filteredOptions() {
            if (!this.query || this.query.trim() === '') {
                return this.options;
            }
            const q = this.query.trim().toLowerCase();
            return this.options.filter(opt => String(opt).toLowerCase().includes(q));
        },
        select(opt) {
            this.query = opt;
            @if ($model)
            {{ $model }} = opt;
            @endif
            this.open = false;
        },
        onInput(e) {
            this.query = e.target.value;
            @if ($model)
            {{ $model }} = this.query;
            @endif
            this.open = true;
        },
        toggle() {
            this.open = !this.open;
            if (this.open) {
                this.$nextTick(() => this.$refs.inputField?.focus());
            }
        }
    }"
    @click.outside="open = false"
>
    <div class="relative w-full">
        <input
            x-ref="inputField"
            type="text"
            id="{{ $id }}"
            name="{{ $name }}"
            :value="query"
            @input="onInput($event)"
            @focus="open = true"
            @click="open = true"
            @keydown.escape="open = false"
            autocomplete="off"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            {{ $attributes->merge(['class' => 'mt-1 block w-full max-w-full min-w-0 rounded-md border-gray-300 pr-10 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder-slate-400 text-sm truncate']) }}
            style="width: 100%; max-width: 100%; box-sizing: border-box;"
        >
        <button
            type="button"
            @click="toggle()"
            class="absolute inset-y-0 right-0 top-1 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none"
            tabindex="-1"
            title="Buka daftar pilihan"
        >
            <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
        </button>
    </div>

    <ul
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute left-0 right-0 z-50 mt-1 max-h-60 w-full min-w-0 max-w-full overflow-y-auto rounded-md border border-gray-300 bg-white py-1 shadow-lg dark:border-slate-600 dark:bg-slate-800 text-sm"
        style="width: 100%; max-width: 100%; box-sizing: border-box;"
    >
        <template x-for="(item, idx) in filteredOptions" :key="idx">
            <li
                @click="select(item)"
                class="cursor-pointer px-3 py-2 text-left text-sm text-gray-800 dark:text-gray-200 hover:bg-indigo-600 hover:text-white dark:hover:bg-indigo-600 dark:hover:text-white transition-colors duration-150 flex items-center justify-between"
                :class="{ 'bg-indigo-50 text-indigo-700 font-semibold dark:bg-slate-700 dark:text-white': query === item }"
            >
                <span x-text="item" class="truncate"></span>
                <i x-show="query === item" class="fas fa-check text-xs ml-2 text-indigo-600 dark:text-indigo-400"></i>
            </li>
        </template>
        <template x-if="filteredOptions.length === 0">
            <li class="px-3 py-2 text-xs text-gray-500 dark:text-gray-400 italic">
                Pilihan tidak ditemukan — ketikan Anda <span class="font-semibold text-gray-700 dark:text-gray-200" x-text="'&quot;' + query + '&quot;'"></span> akan digunakan sebagai nilai baru.
            </li>
        </template>
    </ul>
</div>
