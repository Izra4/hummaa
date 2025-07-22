<header class="bg-white shadow-md relative z-10">
    {{-- Padding diubah (px-6 -> px-4 sm:px-8 lg:px-12) agar lebih lebar di layar besar --}}
    <nav class="container mx-auto flex items-center justify-between px-4 sm:px-8 lg:px-12 py-4">

        <div class="flex items-center space-x-10">
            <a href="/">
                <img src="{{ asset('images/logo-biru.png') }}" alt="Logo" class="h-10 w-auto">
            </a>

            <ul class="hidden md:flex items-center space-x-8">
                <li><a href="{{ route('home') }}" class="text-gray-700 hover:text-main-bg font-semibold transition-colors duration-300">Beranda</a></li>
                <li><a href="{{ route('materials') }}" class="text-gray-700 hover:text-main-bg font-semibold transition-colors duration-300">Materi</a></li>
                <li><a href="{{ route('question-bank') }}" class="text-gray-700 hover:text-main-bg font-semibold transition-colors duration-300">Bank Soal</a></li>
                <li><a href="{{ route('tryouts') }}" class="text-gray-700 hover:text-main-bg font-semibold transition-colors duration-300">Tryout</a></li>
                <li><a href="{{ route('forum') }}" class="text-gray-700 hover:text-main-bg font-semibold transition-colors duration-300">Forum</a></li>
            </ul>
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
