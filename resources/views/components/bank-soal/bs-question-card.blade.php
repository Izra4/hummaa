@props(['judul', 'jumlahSoal', 'tryoutUrl' => '#', 'belajarUrl' => '#'])

<div class="w-full max-w-md overflow-hidden rounded-md border border-gray-200 bg-white shadow-lg">

    <div class="flex items-center justify-between border-b border-gray-200 bg-gray-100 p-5">
        <h3 class="text-lg font-semibold text-gray-900">
            {{ $judul }}
        </h3>
        <span class="text-sm text-gray-500">
            {{ $jumlahSoal }} Soal
        </span>
    </div>

    <div class="flex gap-4 p-5">
        <a href="{{ $tryoutUrl }}"
            class="flex flex-1 items-center justify-center rounded-full bg-main-bg px-4 py-3 font-semibold text-white transition-opacity duration-200 hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-[#00A991] focus:ring-offset-2">
            MODE TRYOUT
        </a>

        <a href="{{ $belajarUrl }}"
            class="flex flex-1 items-center justify-center rounded-full border border-main-bg bg-white px-4 py-3 font-semibold text-main-bg transition-colors duration-200 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-[#4285F4] focus:ring-offset-2">
            MODE BELAJAR
        </a>
    </div>

</div>