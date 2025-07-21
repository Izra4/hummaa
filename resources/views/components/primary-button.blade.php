<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center
w-full px-4 py-3 bg-main-blue-button border border-transparent rounded-lg font-semibold text-sm text-white
uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none
focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
