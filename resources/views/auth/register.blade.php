<x-guest-layout>
    <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">
        Daftar Akun Anda!
    </h1>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name">
                Nama Lengkap <span class="text-red-500">*</span>
            </x-input-label>
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nama Lengkap"/>
            <p class="text-xs text-gray-500 mt-1">Masukan nama asli Anda, nama akan digunakan pada data sertifikat.</p>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email">
                Email <span class="text-red-500">*</span>
            </x-input-label>
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Alamat Email"/>
            <p class="text-xs text-gray-500 mt-1">Gunakan alamat email aktif anda.</p>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password">
                Password <span class="text-red-500">*</span>
            </x-input-label>
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" placeholder="************"/>
            <p class="text-xs text-gray-500 mt-1">Gunakan minimal 8 karakter dengan kombinasi huruf dan angka.</p>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation">
                Konfirmasi Password <span class="text-red-500">*</span>
            </x-input-label>
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="************"/>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button>
                {{-- Di desain tertulis "Masuk", tapi "Daftar" lebih sesuai konteks --}}
                {{ __('Daftar') }}
            </x-primary-button>
        </div>

        <div class="flex items-center my-4">
            <div class="flex-grow border-t border-gray-200"></div>
            <span class="mx-4 text-xs text-gray-500">Atau</span>
            <div class="flex-grow border-t border-gray-200"></div>
        </div>

        <div>
            <x-google-button href="#"> {{-- <-- Ganti href ke route Google Auth --}}
                {{ __('Daftar dengan Google') }}
            </x-google-button>
        </div>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold text-gray-800 underline hover:text-blue-600">
                    Masuk Sekarang
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
