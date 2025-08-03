@props(['selected' => false])

<a {{ $attributes->merge([
    'class' => 'cursor-pointer block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-gray-300 
    hover:bg-gray-100 dark:hover:bg-primary/70 hover:text-gray-900 dark:hover:text-gray-100 
    focus:outline-none focus:bg-gray-100 dark:focus:bg-primary/70 
    transition duration-150 ease-in-out ' .
        ($selected ? 'bg-indigo-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100 font-semibold' : '')
]) }}>{{ $slot }}</a>
