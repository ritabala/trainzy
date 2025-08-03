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
        class="tracking-wider group relative flex items-center gap-2.5 rounded-md py-2.5 px-3 dark:text-gray-300 transition-all duration-200 ease-in-out {{ $this->isActive() ? '  font-medium bg-gray-900 text-gray-100 dark:bg-gray-900/40 dark:border dark:border-b dark:border-gray-500/40' : 'hover:text-gray-800 hover:bg-gray-100 text-gray-700 dark:text-gray-300 dark:hover:bg-gray-500/20' }}"
    >
        {!! $icon !!}
        {{ $title }}

        @if ($hasDropdown)
        <svg
            class="absolute right-4 top-1/2 -translate-y-1/2 fill-current transition-transform duration-200"
            :class="{ 'rotate-180': open }"
            width="20"
            height="20"
            viewBox="0 0 20 20"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M4.41107 6.9107C4.73651 6.58527 5.26414 6.58527 5.58958 6.9107L10.0003 11.3214L14.4111 6.91071C14.7365 6.58527 15.2641 6.58527 15.5896 6.91071C15.915 7.23614 15.915 7.76378 15.5896 8.08922L10.5896 13.0892C10.2641 13.4147 9.73651 13.4147 9.41107 13.0892L4.41107 8.08922C4.08563 7.76378 4.08563 7.23614 4.41107 6.9107Z"
            />
        </svg>
        @endif
    </a>

    @if ($hasDropdown)
    <div
        x-cloak
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        class="transform overflow-hidden"
    >
        <ul class="my-4 flex flex-col gap-2.5 pl-6">
            @foreach ($dropdownItems as $item)
                @php
                    $isItemActive = $this->isDropdownItemActive($item);
                    $itemClasses = $isItemActive 
                        ? 'text-gray-800 before:bg-white hover:font-semibold font-semibold tracking-wider dark:text-gray-500' 
                        : 'before:bg-gray-600 hover:before:bg-white hover:text-gray-800 hover:font-semibold tracking-wider';
                @endphp
                <li class="relative before:content-[''] before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2  {{ $itemClasses }}">
                    <a
                        href="{{ route($item['link']) }}"
                        class="group relative flex items-center gap-2.5 rounded-md px-4 text-gray-700 dark:text-gray-300 transition-all duration-200 ease-in-out {{ $itemClasses }}"
                    >
                        {{ $item['title'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    @endif
</li>
