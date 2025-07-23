<x-app-layout>
    @php
        $activeTab = request()->query('tab', 'untuk-saya');

        $forMePosts = [
            [
                'title' => 'Strategi Menjawab Soal Figural dalam Tes TIU',
                'time' => '2 Jam yang lalu',
                'image' => 'code-image.png',
                'content' => 'Saya merasa kesulitan memahami pola pada soal figural, terutama saat bentuknya mulai diputar atau dicerminkan. Kadang pilihan jawabannya mirip semua dan membingungkan. Ada tips atau strategi khusus untuk menyelesaikannya lebih cepat?',
                'best_comment' => [
                    'author_name' => '@izzul',
                    'author_avatar' => 'avatar-izzul.png',
                    'content' => 'Coba identifikasi perubahan dari gambar pertama ke gambar kedua (rotasi, pencerminan, atau penambahan elemen), lalu terapkan pola itu ke gambar berikutnya. Biasanya polanya berulang.'
                ],
                'reply_count' => 5,
                'author_name' => 'Ikhlasul Amal',
                'author_avatar' => 'ikhlasul-amal.png'
            ],
        ];

        $savedPosts = [
            [
                'title' => 'Tips Manajemen Waktu Saat Ujian, Ada yang Punya Pengalaman?',
                'time' => '1 Hari yang lalu',
                'image' => null, // Postingan ini tidak punya gambar
                'content' => 'Setiap kali tryout, saya selalu kehabisan waktu di bagian akhir. Padahal soalnya tidak terlalu sulit, tapi saya terlalu lama di beberapa nomor. Mungkin ada yang punya tips jitu untuk manajemen waktu?',
                'best_comment' => [
                    'author_name' => '@budi_sukses',
                    'author_avatar' => 'avatar-budi.png',
                    'content' => 'Saran saya, kerjakan soal yang paling mudah dulu untuk membangun momentum. Jangan terpaku pada satu soal lebih dari 2 menit. Kalau sulit, tandai dan tinggalkan dulu, nanti kembali lagi jika ada waktu sisa.'
                ],
                'reply_count' => 8,
                'author_name' => 'Citra Lestari',
                'author_avatar' => 'avatar-citra.png'
            ],
        ];

        $postsToDisplay = ($activeTab == 'disimpan') ? $savedPosts : $forMePosts;

    @endphp

    <div class="bg-gray-50">
        <div class="container mx-auto grid grid-cols-1 gap-8 px-6 py-12 lg:grid-cols-4">
            <aside class="space-y-6 lg:col-span-1">
                <div class="space-y-2 rounded-lg border border-gray-200 p-4">
                    <x-forum.sidebar
                        href="{{ route('forum', ['tab' => 'untuk-saya']) }}"
                        :active="$activeTab == 'untuk-saya'"
                        icon-inactive="heart.png"
                        icon-active="heart-filled.png">
                        Untuk Saya
                    </x-forum.sidebar>

                    <x-forum.sidebar
                        href="{{ route('forum', ['tab' => 'disimpan']) }}"
                        :active="$activeTab == 'disimpan'"
                        icon-inactive="save.png"
                        icon-active="save-filled.png">
                        Disimpan
                    </x-forum.sidebar>
                </div>
                <x-forum.popular-topics />
            </aside>

            <main class="space-y-6 lg:col-span-3">
                <div class="flex items-center gap-x-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                    <input type="text" placeholder="Tuliskan Topik" class="w-full border-0 bg-transparent p-0 text-gray-900 placeholder:text-gray-400 focus:ring-0">
                    <button class="flex-none rounded-full bg-main-bg p-2 text-white hover:bg-teal-700">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" /></svg>
                    </button>
                </div>

                @forelse($postsToDisplay as $post)
                    <x-forum.post-card :post="$post" />
                @empty
                    <div class="text-center text-gray-500 py-12">
                        <p>Tidak ada postingan yang disimpan.</p>
                    </div>
                @endforelse
            </main>
        </div>
    </div>
</x-app-layout>
