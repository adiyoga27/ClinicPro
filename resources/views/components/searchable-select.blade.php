@props(['options', 'placeholder' => 'Pilih...', 'wireModel'])

<div x-data="{
        open: false,
        search: '',
        selected: @entangle($wireModel),
        options: {{ json_encode($options) }},
        get filteredOptions() {
            if (this.search === '') return this.options;
            return this.options.filter(i => i.label.toLowerCase().includes(this.search.toLowerCase()));
        },
        get selectedLabel() {
            let opt = this.options.find(i => i.value == this.selected);
            return opt ? opt.label : '{{ $placeholder }}';
        },
        selectOption(value) {
            this.selected = value;
            this.open = false;
            this.search = '';
        }
    }"
    class="relative w-full"
    @click.away="open = false">
    
    <button type="button" @click="open = !open"
        class="flex items-center justify-between w-full px-4 py-2.5 bg-surface-50 dark:bg-surface-800/50 border border-surface-200 dark:border-white/10 rounded-xl text-left text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-surface-900 dark:text-white">
        <span x-text="selectedLabel" class="truncate"></span>
        <svg class="w-4 h-4 text-surface-500" :class="open ? 'rotate-180' : ''" style="transition: transform 0.2s;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open" x-transition.opacity.duration.200ms
        class="absolute z-50 w-full mt-2 bg-white dark:bg-surface-900 border border-surface-200 dark:border-white/10 rounded-xl shadow-xl overflow-hidden"
        style="display: none;">
        
        <div class="p-2 border-b border-surface-200 dark:border-white/10">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-surface-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" x-model="search" placeholder="Cari..." 
                    class="w-full pl-9 pr-4 py-2 bg-surface-50 dark:bg-surface-800 border-none rounded-lg text-sm text-surface-900 dark:text-white focus:ring-1 focus:ring-primary-500">
            </div>
        </div>

        <ul class="max-h-60 overflow-y-auto p-2 space-y-1">
            <template x-for="option in filteredOptions" :key="option.value">
                <li>
                    <button type="button" @click="selectOption(option.value)"
                        class="w-full text-left px-3 py-2 text-sm rounded-lg hover:bg-surface-100 dark:hover:bg-surface-800 transition-colors text-surface-700 dark:text-white"
                        :class="selected == option.value ? 'bg-primary-50 !text-primary-700 dark:bg-primary-500/10 dark:!text-primary-400 font-medium' : ''">
                        <span x-text="option.label"></span>
                    </button>
                </li>
            </template>
            <template x-if="filteredOptions.length === 0">
                <li class="px-3 py-4 text-sm text-center text-surface-500">Pencarian tidak ditemukan</li>
            </template>
        </ul>
    </div>
</div>
