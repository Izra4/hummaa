<a {{ $attributes->merge(['class' => 'flex items-center justify-center w-full px-4 py-3 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150']) }}>

    {{-- Google Logo SVG --}}
    <svg class="w-5 h-5 me-3" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="google" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 488 512">
        <path fill="currentColor" d="M488 261.8C488 403.3 381.5 512 244 512 110.3 512 0 401.7 0 265.4c0-13.2 1-26.4 2.8-39.3h241.2v73.4h-93.8c14.7 41.1 56.6 70.8 105.8 70.8 62.2 0 113.2-51.1 113.2-113.4s-51-113.4-113.2-113.4c-28.2 0-54.3 10.6-74.3 28.4l-63-62.9C143.1 49.3 189.6 24 244 24c115.3 0 211.8 83.5 237.2 192.8l4.4 25z"></path>
    </svg>

    {{ $slot }}
</a>
