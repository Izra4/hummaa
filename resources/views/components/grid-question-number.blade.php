{{-- resources/views/components/nomor-soal-grid.blade.php --}}
<div class="flex h-full flex-col rounded-lg bg-white p-6 shadow-sm">
    <div class="mb-6 flex items-center gap-4">
        <div class="bg-ujian-gray-100 rounded-lg p-3">
            {{-- Ganti dengan SVG atau icon yang sesuai --}}
            <svg class="text-ujian-blue h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                </path>
            </svg>
        </div>
        <div>
            <h2 class="text-ujian-gray-700 font-bold">Tes Potensi Akademik</h2>
            <p class="text-ujian-gray-500 text-sm" x-text="totalQuestions + ' Soal'"></p>
        </div>
    </div>

    <div class="mb-6 grid grid-cols-5 gap-3">
        <template x-for="i in Array.from({ length: 50 }, (_, k) => k + 1)" :key="i">
            <button @click="changeQuestion(i - 1)"
                class="flex h-10 w-10 items-center justify-center rounded-md font-bold transition-colors duration-200"
                :class="{
                    'bg-ujian-green-bg bg-opacity-50 border border-ujian-green-border text-black': getQuestionStatus(
                        i) === 'active',
                    'bg-ujian-green-bg border border-ujian-green-border text-white': getQuestionStatus(
                        i) === 'answered',
                    'bg-white text-ujian-gray-700 border border-ujian-gray-200 hover:bg-ujian-gray-100': getQuestionStatus(
                        i) === 'unanswered'
                }"
                x-text="i">
            </button>
        </template>
    </div>

    <div class="mt-auto">
        <button @click="isModalOpen = true"
            class="bg-ujian-blue w-full rounded-lg py-3 font-bold text-white transition-colors duration-200 hover:bg-blue-700">
            Kumpulkan
        </button>
    </div>
</div>
