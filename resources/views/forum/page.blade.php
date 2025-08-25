<x-app-layout>
    @php

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
                        <p>Tidak ada postingan.</p>
                    </div>
                @endforelse

                {{-- pagination opsional --}}
                @if($paginator)
                    <div>
                        {{ $paginator->withQueryString()->links() }}
                    </div>
                @endif
            </main>
        </div>
    </div>
</x-app-layout>
