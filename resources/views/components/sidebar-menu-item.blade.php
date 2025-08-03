@php
    $isDropdownItemActive = $hasDropdown && isset($dropdownItems) && collect($dropdownItems)->contains(fn($item) => $this->isDropdownItemActive($item));
    $isOpen = $isDropdownItemActive;
@endphp

<li x-data="{ 
    open: @js($isOpen),
    title: @js($title),
    toggle() {
        this.open = !this.open;
        $dispatch('click-menu-item', { title: this.title });
    }
}">
    <a
        href="{{ $link ? route($link) : '#' }}"
        @if (!$link) @click.prevent="toggle()" @endif
        class="group relative flex items-center gap-3 rounded-xl py-3 px-4 text-sm font-medium transition-all duration-200 ease-in-out {{ $isActive ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg shadow-blue-500/25' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800/50' }}"
    >
        <div class="flex-shrink-0 {{ $isActive ? 'text-white' : 'text-slate-500 group-hover:text-blue-600 dark:text-slate-400 dark:group-hover:text-blue-400' }}">
            {!! $icon !!}
        </div>
        <span class="flex-1">{{ $title }}</span>

        @if ($hasDropdown)
        <svg
            class="flex-shrink-0 w-4 h-4 transition-transform duration-200 {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-blue-600 dark:text-slate-500 dark:group-hover:text-blue-400' }}"
            :class="{ 'rotate-180': open }"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
        @endif

        @if ($isActive)
        <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-white rounded-l-full"></div>
        @endif
    </a>

    @if ($hasDropdown)
    <div
        x-cloak
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="overflow-hidden"
    >
        <ul class="mt-2 ml-4 space-y-1 border-l-2 border-slate-200 dark:border-slate-700 pl-4">
            @foreach ($dropdownItems as $item)
                @php
                    $isItemActive = $this->isDropdownItemActive($item);
                    $itemClasses = $isItemActive 
                        ? 'text-blue-600 dark:text-blue-400 font-medium bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800' 
                        : 'text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 border-transparent';
                @endphp
                <li>
                    <a
                        href="{{ route($item['link']) }}"
                        class="group flex items-center gap-2 rounded-lg px-3 py-2 text-sm transition-all duration-200 ease-in-out border {{ $itemClasses }}"
                    >
                        <div class="w-1.5 h-1.5 rounded-full {{ $isItemActive ? 'bg-blue-600 dark:bg-blue-400' : 'bg-slate-300 dark:bg-slate-600 group-hover:bg-blue-600 dark:group-hover:bg-blue-400' }} transition-colors"></div>
                        {{ $item['title'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    @endif
</li>