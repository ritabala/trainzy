@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white dark:bg-gray-700', 'dropdownClasses' => '', 'height' => '300', 'selectedValue' => null])

@php
$alignmentClasses = match ($align) {
    'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
    'top' => 'origin-top',
    'none', 'false' => '',
    default => 'ltr:origin-top-right rtl:origin-top-left end-0',
};

$width = match ($width) {
    '48' => 'w-48',
    '60' => 'w-60',
    default => 'w-48',
};

// Ensure height is a number
$height = is_numeric($height) ? $height : 300;
@endphp

<div class="relative" x-data="{ 
    open: false,
    position: 'bottom',
    selectedValue: @js($selectedValue),
    checkPosition() {
        const trigger = this.$refs.trigger;
        const dropdown = this.$refs.dropdown;
        if (!trigger || !dropdown) return;

        const triggerRect = trigger.getBoundingClientRect();
        const viewportHeight = window.innerHeight;
        const viewportWidth = window.innerWidth;
        
        // Use height from prop for calculations
        const dropdownHeight = {{ $height }};
        
        // Get the actual rendered width of the dropdown
        const dropdownWidth = dropdown.offsetWidth;

        // Calculate available space in all directions
        const spaceBelow = viewportHeight - triggerRect.bottom;
        const spaceAbove = triggerRect.top;
        const spaceRight = viewportWidth - triggerRect.right;
        const spaceLeft = triggerRect.left;

        // Get the alignment preference
        const alignment = '{{ $align }}';

        // Minimum distance from viewport edges
        const minEdgeDistance = 16; // 1rem

        // Check if we're on mobile (viewport width < 640px)
        const isMobile = viewportWidth < 640;

        // On mobile, prefer right alignment unless there's not enough space
        if (isMobile) {
            if (spaceRight >= dropdownWidth + minEdgeDistance) {
                this.position = 'bottom';
                dropdown.style.left = '0';
                dropdown.style.right = 'auto';
            } else if (spaceLeft >= dropdownWidth + minEdgeDistance) {
                this.position = 'bottom';
                dropdown.style.left = 'auto';
                dropdown.style.right = '0';
            } else if (spaceAbove >= dropdownHeight + minEdgeDistance) {
                this.position = 'top';
                dropdown.style.left = '0';
                dropdown.style.right = 'auto';
            } else {
                // If no good position found, center the dropdown
                this.position = 'bottom';
                const centerOffset = (dropdownWidth - triggerRect.width) / 2;
                dropdown.style.left = `-${centerOffset}px`;
                dropdown.style.right = 'auto';
            }
            return;
        }

        // Desktop positioning logic
        if (spaceBelow >= dropdownHeight + minEdgeDistance) {
            this.position = 'bottom';
            if (alignment === 'left') {
                dropdown.style.left = '0';
                dropdown.style.right = 'auto';
            } else {
                dropdown.style.left = 'auto';
                dropdown.style.right = '0';
            }
        } else if (spaceAbove >= dropdownHeight + minEdgeDistance) {
            this.position = 'top';
            if (alignment === 'left') {
                dropdown.style.left = '0';
                dropdown.style.right = 'auto';
            } else {
                dropdown.style.left = 'auto';
                dropdown.style.right = '0';
            }
        } else {
            // If vertical positioning would cause cutoff, try horizontal positioning
            if (spaceRight >= dropdownWidth + minEdgeDistance) {
                this.position = 'right';
            } else if (spaceLeft >= dropdownWidth + minEdgeDistance) {
                this.position = 'left';
            } else {
                // If no good position found, center the dropdown
                this.position = 'bottom';
                const centerOffset = (dropdownWidth - triggerRect.width) / 2;
                dropdown.style.left = `-${centerOffset}px`;
                dropdown.style.right = 'auto';
            }
        }
    }
}" @click.away="open = false" @close.stop="open = false">
    <div @click="open = !open; if(open) $nextTick(() => checkPosition())" x-ref="trigger">
        {{ $trigger }}
    </div>

    <div x-show="open"
            x-ref="dropdown"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            :class="{
                'absolute z-[99] mt-2': position === 'bottom',
                'absolute z-[99] mb-2 bottom-full': position === 'top',
                'absolute z-[99] ml-2 left-full top-0': position === 'right',
                'absolute z-[99] mr-2 right-full top-0': position === 'left'
            }"
            class="{{ $width }} rounded-md shadow-lg {{ $dropdownClasses }}"
            style="display: none;"
            @click="open = false">
        <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }} overflow-y-auto" style="max-height: {{ $height }}px; position: relative; isolation: isolate;">
            {{ $content }}
        </div>
    </div>
</div>
