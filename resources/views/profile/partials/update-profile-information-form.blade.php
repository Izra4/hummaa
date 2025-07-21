<section>
    <div class="bg-white p-8 rounded-lg shadow-md">
        <header>
            <h2 class="text-2xl font-semibold text-gray-900">
                {{ __('Detail Profil') }}
            </h2>
        </header>

        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <div>
                <x-input-label for="photo">
                    Foto Profil <span class="text-red-500">*</span>
                </x-input-label>
                <div class="mt-2 flex items-center space-x-6">
                    <img id="photo-preview" class="h-24 w-24 rounded-full object-cover" src="" alt="Foto Profil">
                    <div>
                        <x-primary-button px="px-2" py="py-2" rounded="rounded-xl" uc="" tracking=""
                                          type="button" onclick="document.getElementById('photo').click();">
                            {{ __('Unggah Foto') }}
                        </x-primary-button>
                        <input type="file" id="photo" name="photo" class="hidden" onchange="previewImage(event)">
                    </div>
                    <button class="text-main-blue-button rounded-lg px-2 py-2 hover:bg-gray-50 transition ease-in-out duration-150
                    font-semibold">
                        Gunakan Karakter Avatar
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-2">Foto profil kamu disarankan memiliki rasio 1:1 atau berukuran tidak lebih dari 2MB.</p>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="first_name">
                        Nama Depan <span class="text-red-500">*</span>
                    </x-input-label>
                    <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" value="Joko" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                </div>
                <div>
                    <x-input-label for="last_name">
                    Nama Belakang <span class="text-red-500">*</span>
                    </x-input-label>
                    <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" value="Widodo" required />
                    <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                </div>
            </div>

            <div>
                <x-input-label for="email">
                    Alamat Email <span class="text-red-500">*</span>
                </x-input-label>
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full bg-gray-100" value="hidupjokowi@um.ac.id" required autocomplete="username" disabled />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>

            <div>
                <x-input-label for="whatsapp">
                    No WhatsApp <span class="text-red-500">*</span>
                </x-input-label>
                <x-text-input id="whatsapp" name="whatsapp" type="text" class="mt-1 block w-full" value="old('whatsapp', $user->whatsapp)" />
                <x-input-error class="mt-2" :messages="$errors->get('whatsapp')" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="birth_date" :value="__('Tanggal Lahir')"></x-input-label>
                    <x-text-input id="birth_date" name="birth_date" type="date" class="mt-1 block w-full" value="old('birth_date', $user->birth_date)" />
                    <x-input-error class="mt-2" :messages="$errors->get('birth_date')" />
                </div>
                <div>
                    <x-input-label :value="__('Jenis Kelamin')" />
                    <div class="flex items-center space-x-6 mt-2">
                        <label for="male" class="flex items-center">
                            <input id="male" name="gender" type="radio" value="Laki-Laki" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <span class="ms-2 text-sm text-gray-700">Laki-Laki</span>
                        </label>
                        <label for="female" class="flex items-center">
                            <input id="female" name="gender" type="radio" value="Perempuan" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <span class="ms-2 text-sm text-gray-700">Perempuan</span>
                        </label>
                    </div>
                </div>
            </div>

            <div>
                <x-input-label for="password" :value="__('Ganti Password')" />
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full"/>
                <x-input-error class="mt-2" :messages="$errors->get('password')" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Konfirmasi Password')" />
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full"/>
                <x-input-error class="mt-2" :messages="$errors->get('password')" />
            </div>

            <div class="flex items-center justify-end gap-4 mt-8">
                <x-buttons.danger-button :href="route('logout')">
                    {{ __('Logout') }}
                </x-buttons.danger-button>
                <x-primary-button px="px-6" py="py-2" width="" uc="" tracking="">{{ __('Simpan') }}</x-primary-button>
            </div>
        </form>
    </div>
</section>
