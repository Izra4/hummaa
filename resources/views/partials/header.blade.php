<header class="bg-white shadow-md relative z-10">
    <nav class="container mx-auto flex items-center justify-between px-6 py-4">

        <div class="flex items-center space-x-10">
            <a href="/">
                {{-- TODO: Ganti placeholder ini dengan kode SVG logo kamu --}}
                <svg class="h-8 w-auto text-main-bg" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1.29 15.29c-.39.39-1.02.39-1.41 0l-3-3c-.39-.39-.39-1.02 0-1.41s1.02-.39 1.41 0L10 14.17l5.29-5.29c.39-.39 1.02-.39 1.41 0s.39 1.02 0 1.41l-6 6z"/>
                </svg>
            </a>

            <ul class="hidden md:flex items-center space-x-8">
                <li><a href="#" class="text-gray-700 hover:text-main-bg font-medium transition-colors duration-300">Beranda</a></li>
                <li><a href="#" class="text-gray-700 hover:text-main-bg font-medium transition-colors duration-300">Materi</a></li>
                <li><a href="#" class="text-gray-700 hover:text-main-bg font-medium transition-colors duration-300">Bank Soal</a></li>
                <li><a href="#" class="text-gray-700 hover:text-main-bg font-medium transition-colors duration-300">Tryout</a></li>
                <li><a href="#" class="text-gray-700 hover:text-main-bg font-medium transition-colors duration-300">Forum</a></li>
            </ul>
        </div>

        <div class="hidden md:flex items-center space-x-4">
            <a href="{{ route('login') }}" class="px-6 py-2 text-sm font-semibold text-main-bg border border-main-bg rounded-full hover:bg-teal-50 transition-colors duration-300">
                Log in
            </a>
            <a href="{{ route('register') }}" class="px-6 py-2 text-sm font-semibold text-white bg-main-bg rounded-full hover:bg-teal-700 transition-colors duration-300">
                Sign in
            </a>
        </div>

        <div class="md:hidden">
            <button class="text-gray-700 hover:text-gray-900 focus:outline-none">
                <svg class="h-6 w-6" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>
    </nav>
</header>
