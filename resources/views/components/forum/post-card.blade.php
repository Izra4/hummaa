@props(['post'])

<style>[x-cloak]{display:none !important}</style>

<div x-data="{ open:false }" x-effect="document.body.classList.toggle('overflow-hidden', open)"
     @keydown.escape.window="open=false"
     class="rounded-lg border border-l-4 border-y-2 border-r-2 border-main-bg bg-white p-6 shadow-sm">

    <div class="flex items-center justify-between">
        <p class="text-2xl font-bold text-gray-900">{{ $post['title'] }}</p>
        <span class="text-xs text-gray-500">{{ $post['time'] }}</span>
    </div>

    @if(!empty($post['image']))
        <button type="button" class="mt-3 block w-full focus:outline-none" @click="open=true" aria-label="Lihat gambar">
            <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}" class="h-40 w-full rounded-md object-cover">
        </button>

        <div x-show="open" x-cloak
             x-transition.opacity
             class="fixed inset-0 z-[1000]">
            <div class="absolute inset-0 bg-black/60" @click="open=false"></div>

            <div class="absolute inset-0 flex items-center justify-center p-4">
                <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}"
                     class="max-h-[90vh] max-w-[90vw] rounded-lg object-contain shadow-2xl">
                <button type="button"
                        class="absolute right-4 top-4 rounded-full bg-black/70 p-2 text-white hover:bg-black/90"
                        @click="open=false" aria-label="Tutup">
                    {{-- ikon close sederhana --}}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                        <path fill-rule="evenodd" d="M10 8.586 4.293 2.879A1 1 0 0 0 2.88 4.293L8.586 10l-5.707 5.707a1 1 0 1 0 1.414 1.414L10 11.414l5.707 5.707a1 1 0 0 0 1.414-1.414L11.414 10l5.707-5.707A1 1 0 0 0 15.707 2.88L10 8.586z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <p class="mt-4 text-sm leading-6 text-gray-600">{{ $post['content'] }}</p>

    @if(data_get($post, 'best_comment'))
        <div class="mt-4 rounded-lg bg-gray-50 p-4">
            <div class="flex items-center gap-x-3">
                <img src="{{ asset('images/' . (data_get($post, 'best_comment.author_avatar') ?? 'default-avatar.png')) }}"
                     alt="Avatar" class="h-6 w-6 rounded-full">
                <span class="text-sm font-semibold text-gray-800">{{ data_get($post, 'best_comment.author_name') }}</span>
            </div>
            <p class="mt-2 text-sm text-gray-600">{{ data_get($post, 'best_comment.content') }}</p>
        </div>
    @endif

    @if(!is_null(data_get($post,'reply_count')))
        <p class="mt-4 text-xs text-gray-500">{{ $post['reply_count'] }} Balasan lainnya</p>
    @endif

    <div class="mt-2 flex items-center gap-x-4">
        <img referrerPolicy="no-referrer"
             src="{{ Auth::user()->avatar ? asset(Auth::user()->profile_picture_url) : asset('images/default-profile.jpeg') }}"
             alt="{{ Auth::user()->name }}" class="h-8 w-8 rounded-full">
        <input type="text" placeholder="Ketik balasan di sini..."
               class="w-full rounded-full border-gray-300 bg-gray-100 px-4 py-2 text-sm focus:border-main-bg focus:ring-main-bg">
    </div>

    <div class="mt-4 flex items-center justify-between border-t border-gray-200 pt-4">
        <button>
            <svg class="h-5 w-5 text-gray-500 hover:text-gray-700" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M15 8a3 3 0 0 1-2.824 2.995L12 11H8a3 3 0 0 1 0-6h4a3 3 0 0 1 3 3Zm-7 4h4a3 3 0 1 1 0 6H8a3 3 0 1 1 0-6Z"/>
            </svg>
        </button>
        <div class="flex items-center text-xs text-gray-500">
            <span>Ditulis oleh</span>
            <span class="ms-1 font-semibold text-gray-800">{{ $post['author_name'] }}</span>
            <img referrerPolicy="no-referrer"
                 src="{{ Auth::user()->avatar ? asset(Auth::user()->profile_picture_url) : asset('images/default-profile.jpeg') }}"
                 alt="{{ Auth::user()->name }}" class="ms-2 h-6 w-6 rounded-full">
        </div>
    </div>
</div>
