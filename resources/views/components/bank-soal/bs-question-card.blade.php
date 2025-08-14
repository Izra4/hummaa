@props(['judul', 'jumlahSoal', 'tryoutUrl' => '#', 'belajarUrl' => '#', 'forumUrl' => '#'])

<div class="w-full max-w-md overflow-hidden rounded-md border border-gray-200 bg-white shadow-lg">

    <div class="flex items-center justify-between border-b border-gray-200 bg-gray-100 p-5">
        <h3 class="text-lg font-semibold text-gray-900">
            {{ $judul }}
        </h3>
        <span class="text-sm text-gray-500">
            {{ $jumlahSoal }} Soal
        </span>
    </div>

    <div class="flex flex-col gap-4 p-5">
        <div class="flex w-full gap-4">
            <a href="{{ $tryoutUrl }}"
                class="bg-main-bg flex flex-1 items-center justify-center rounded-full px-4 py-3 font-semibold text-white transition-opacity duration-200 hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-[#00A991] focus:ring-offset-2">
                MODE TRYOUT
            </a>

            <a href="{{ $belajarUrl }}"
                class="border-main-bg text-main-bg flex flex-1 items-center justify-center rounded-full border bg-white px-4 py-3 font-semibold transition-colors duration-200 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-[#4285F4] focus:ring-offset-2">
                MODE BELAJAR
            </a>
        </div>

        <a href="{{ $forumUrl }}"
            class="border-main-bg text-main-bg flex w-full items-center justify-center rounded-full border bg-white px-4 py-3 font-semibold transition-colors duration-200 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#00A991] focus:ring-offset-2">
            <svg viewBox="0 0 64 64" class="mr-2 h-5 w-5" id="icons" xmlns="http://www.w3.org/2000/svg" fill="#2C7A7B">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                    <defs>
                        <style>
                            .cls-1 {
                                fill: #2C7A7B;
                            }
                        </style>
                    </defs>
                    <title></title>
                    <path class="cls-1"
                        d="M53,21H48V17a6,6,0,0,0-6-6H11a6,6,0,0,0-6,6V50a2,2,0,0,0,1.19,1.83A2.1,2.1,0,0,0,7,52a2,2,0,0,0,1.35-.52L18,42.7V46a6,6,0,0,0,6,6H45.42l10.51,6.69A2,2,0,0,0,57,59a1.94,1.94,0,0,0,1-.25A2,2,0,0,0,59,57V27A6,6,0,0,0,53,21ZM9,45.48V17a2,2,0,0,1,2-2H42a2,2,0,0,1,2,2V36a2,2,0,0,1-2,2H18a2,2,0,0,0-1.35.52Zm46,7.88-7.93-5A2,2,0,0,0,46,48H24a2,2,0,0,1-2-2V42H42a6,6,0,0,0,6-6V25h5a2,2,0,0,1,2,2Z">
                    </path>
                </g>
            </svg>
            FORUM DISKUSI
        </a>
    </div>

</div>