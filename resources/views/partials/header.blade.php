<header class="bg-white shadow-md relative z-10">
    <nav class="container mx-auto flex items-center justify-between px-4 sm:px-8 lg:px-12 py-4">

        <div class="flex items-center space-x-10">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo-biru.png') }}" alt="Logo" class="h-10 w-auto">
            </a>

            <ul class="hidden md:flex items-center space-x-8">
                <li>
                    <a href="{{ route('home') }}" class="font-semibold transition-colors duration-300 rounded-lg px-4 py-2
                        {{ request()->routeIs('home') ? 'bg-main-bg text-white' : 'text-gray-700 hover:text-main-bg' }}">
                        Beranda
                    </a>
                </li>
                <li>
                    <a href="{{ route('materials') }}" class="font-semibold transition-colors duration-300 rounded-lg px-4 py-2
                        {{ request()->routeIs('materials*') ? 'bg-main-bg text-white' : 'text-gray-700 hover:text-main-bg' }}">
                        Materi
                    </a>
                </li>
                <li>
                    <a href="{{ route('bank-soal') }}" class="font-semibold transition-colors duration-300 rounded-lg px-4 py-2
                        {{ request()->routeIs('bank-soal*') ? 'bg-main-bg text-white' : 'text-gray-700 hover:text-main-bg' }}">
                        Bank Soal
                    </a>
                </li>
                <li>
                    <a href="{{ route('tryouts') }}" class="font-semibold transition-colors duration-300 rounded-lg px-4 py-2
                        {{ request()->routeIs('tryouts*') ? 'bg-main-bg text-white' : 'text-gray-700 hover:text-main-bg' }}">
                        Tryout
                    </a>
                </li>
                <li>
                    <a href="{{ route('forum') }}" class="font-semibold transition-colors duration-300 rounded-lg px-4 py-2
                        {{ request()->routeIs('forum*') ? 'bg-main-bg text-white' : 'text-gray-700 hover:text-main-bg' }}">
                        Forum
                    </a>
                </li>
            </ul>
        </div>

        @guest
            {{-- Tampilkan ini jika user BELUM LOGIN --}}
            <div class="hidden md:flex items-center space-x-4">
                <a href="{{ route('login') }}" class="px-6 py-2 text-sm font-semibold text-main-bg border border-main-bg rounded-full hover:bg-teal-50 transition-colors duration-300">
                    Log in
                </a>
                <a href="{{ route('register') }}" class="px-6 py-2 text-sm font-semibold text-white bg-main-bg rounded-full hover:bg-teal-700 transition-colors duration-300">
                    Sign Up
                </a>
            </div>
        @endguest

        @auth
            <div class="hidden md:flex items-center space-x-4">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                        <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-300"
                             src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('images/default-avatar.png') }}"
                             alt="{{ Auth::user()->name }}">
                    </button>

                    <div x-show="open"
                         @click.away="open = false"
                         x-transition
                         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20"
                         style="display: none;">

                        <div class="px-4 py-2 text-sm text-gray-700">
                            <p class="font-semibold">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <hr>

                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil Saya</a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endauth

        <div class="md:hidden">
            <button class="text-gray-700 hover:text-gray-900 focus:outline-none">
                <svg class="h-6 w-6" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>
    </nav>
</header>
