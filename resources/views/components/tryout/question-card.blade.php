<div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
    <div class="mb-6 flex items-center justify-between border-b border-gray-200 pb-4">
        <div>
            <h2 class="font-bold text-black">
                Pertanyaan <span x-text="currentIndex + 1"></span> dari <span x-text="totalQuestions"></span>
            </h2>
            <p class="text-sm text-gray-500">Silahkan jawab pertanyaan di bawah</p>
        </div>
        <div class="flex items-center gap-2 rounded-lg bg-red-100 px-4 py-2 text-lg font-bold text-red-600">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span x-text="formatTime()"></span>
        </div>
    </div>

    <div x-show="currentQuestion">
        <template x-if="currentQuestion.image">
            <div class="mb-6">
                <img :src="currentQuestion.image" alt="Ilustrasi Soal"
                    class="mx-auto max-h-72 w-full rounded-lg object-contain">
            </div>
        </template>

        <p class="mb-6 text-gray-700" x-text="currentQuestion.text"></p>
    </div>

    <div class="mb-8 space-y-3">
        <template x-if="currentQuestion && currentQuestion.type === 'pilihan_ganda'">
            <div class="space-y-3" :key="currentQuestion.id">
                <template x-for="(optionData, optionKey) in currentQuestion.options" :key="optionKey">
                    <div @click="mode === 'tryout' ? selectAnswer(optionKey, optionData.id) : null"
                        class="flex items-center justify-between gap-4 rounded-lg border p-2 transition-colors"
                        :class="{
                            'cursor-pointer hover:border-main-bg': mode === 'tryout',
                            'cursor-default': mode === 'belajar',
                            'bg-main-bg/50 border-main-bg text-white': mode === 'tryout' && answers[currentQuestion
                                .id] && answers[currentQuestion.id].key === optionKey
                        }">
                        <div class="flex items-center gap-4">
                            <div class="border-ujian-gray-200 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-md border bg-white text-sm font-bold text-black"
                                :class="{
                                    '!border-main-bg/90 !bg-main-bg/90 text-white': mode === 'tryout' && answers[
                                        currentQuestion.id] && answers[currentQuestion.id].key === optionKey
                                }">
                                <span x-text="optionKey"></span>
                            </div>
                            <p class="text-sm font-normal" x-text="optionData.text"></p>
                        </div>
                    </div>
                </template>
            </div>
        </template>

        <template x-if="currentQuestion && currentQuestion.type === 'isian'">
            <div>
                <label for="jawaban_isian" class="text-sm font-bold text-gray-700">Jawaban kamu:</label>
                <textarea id="jawaban_isian" rows="4"
                    class="focus:border-main-blue-button focus:ring-main-blue-button mt-2 block w-full rounded-lg border border-gray-200 p-1"
                    placeholder="Ketik jawaban disini..." :value="answers[currentQuestion.id] ? answers[currentQuestion.id].text : ''"
                    @input.debounce.500ms="selectAnswer(null, null, $event.target.value)"></textarea>
            </div>
        </template>
    </div>

    <div class="flex justify-between">
        <button @click="prevQuestion()" :disabled="currentIndex === 0"
            class="bg-main-blue-button rounded-lg border border-gray-200 px-6 py-2 font-bold text-white transition-colors duration-200 hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
            Kembali
        </button>
        <button @click="nextQuestion()" :disabled="currentIndex === totalQuestions - 1"
            class="bg-main-blue-button rounded-lg px-6 py-2 font-bold text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
            Lanjut
        </button>
    </div>

    <template x-if="mode === 'belajar'">
        <div class="mt-8 rounded-lg border border-gray-200 p-4">
            <h3 class="font-bold text-gray-800">Pembahasan</h3>
            <p class="mt-2 text-gray-600" x-text="currentQuestion.explanation"></p>
        </div>
    </template>
</div>
