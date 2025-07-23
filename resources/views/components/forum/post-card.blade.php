@props(['post'])

<div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-bold text-gray-900">{{ $post['title'] }}</h2>
        <span class="text-xs text-gray-500">{{ $post['time'] }}</span>
    </div>

    @if($post['image'])
        <img src="{{ asset('images/' . $post['image']) }}" alt="Post Image" class="mt-4 w-full rounded-lg object-cover">
    @endif

    <p class="mt-4 text-sm leading-6 text-gray-600">{{ $post['content'] }}</p>

    <div class="mt-4 rounded-lg bg-gray-50 p-4">
        <div class="flex items-center gap-x-3">
            <img src="{{ asset('images/' . $post['best_comment']['author_avatar']) }}" alt="Avatar" class="h-6 w-6 rounded-full">
            <span class="text-sm font-semibold text-gray-800">{{ $post['best_comment']['author_name'] }}</span>
        </div>
        <p class="mt-2 text-sm text-gray-600">{{ $post['best_comment']['content'] }}</p>
    </div>

    <p class="mt-4 text-xs text-gray-500">{{ $post['reply_count'] }} Balasan lainnya</p>
    <div class="mt-2 flex items-center gap-x-4">
        <img src="{{ asset('images/ikhlasul-amal.png') }}" alt="Your Avatar" class="h-8 w-8 rounded-full">
        <input type="text" placeholder="Ketik balasan di sini..." class="w-full rounded-full border-gray-300 bg-gray-100 px-4 py-2 text-sm focus:border-main-bg focus:ring-main-bg">
    </div>

    <div class="mt-4 flex items-center justify-between border-t border-gray-200 pt-4">
        <button>
            <svg class="h-5 w-5 text-gray-500 hover:text-gray-700" ... >{{-- SVG Icon Share --}}</svg>
        </button>
        <div class="flex items-center text-xs text-gray-500">
            <span>Ditulis oleh</span>
            <span class="ms-1 font-semibold text-gray-800">{{ $post['author_name'] }}</span>
            <img src="{{ asset('images/' . $post['author_avatar']) }}" alt="Author Avatar" class="ms-2 h-6 w-6 rounded-full">
        </div>
    </div>
</div>
