<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2  border
border-transparent rounded-md font-medium text-base text-white tracking-wider
active:bg-white-900 focus:outline-none focus:border-white-900 focus:ring ring-gray-300 disabled:opacity-25
transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
