<div class="rounded-lg bg-white p-6 shadow-sm border border-gray-200">
    <div class="border-gray-200 mb-6 flex items-center justify-between border-b pb-4">
        <div>
            <h2 class="text-black font-bold">
                Pertanyaan <span x-text="currentIndex + 1"></span> dari <span x-text="totalQuestions"></span>
            </h2>
            <p class="text-gray-500 text-sm">Silahkan jawab pertanyaan di bawah</p>
        </div>
        <div class="flex items-center gap-2 rounded-lg bg-red-100 px-4 py-2 text-lg font-bold text-red-600">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span x-text="formatTime()"></span>
        </div>
    </div>

    <template x-if="currentQuestion.image">
        <div>
            <p class="text-black mb-4">
                <strong>Petunjuk:</strong> Perhatikan gambar berikut ini, kemudian pilih jawaban yang paling
                menggambarkan
                tindakan Anda jika berada dalam situasi tersebut.
            </p>

            <div class="mb-6">
                <img :src="currentQuestion.image" alt="Ilustrasi Soal"
                    class="mx-auto max-h-72 w-full rounded-lg object-contain">
            </div>
        </div>
    </template>

    <p class="text-gray-700 mb-6" x-text="currentQuestion.text"></p>

    <div class="mb-8 space-y-3">
        <template x-if="currentQuestion.type === 'pilihan_ganda'">
            <div class="space-y-3">
                <template x-for="(text, key) in options" :key="key">
                    <x-tryout.answer-options />
                </template>
            </div>
        </template><template x-if="currentQuestion.type === 'isian'">
            <div>
                <label for="jawaban_isian" class="text-gray-700 text-sm font-bold">Jawaban kamu:</label>
                <textarea id="jawaban_isian" rows="4"
                    class="border border-gray-200 focus:border-main-blue-button focus:ring-main-blue-button mt-2 block w-full rounded-lg p-1"
                    placeholder="Ketik jawaban disini..." {{-- 'x-model' menghubungkan nilai textarea dengan state 'answers' --}} x-model="answers[currentQuestion.id]"></textarea>
            </div>
        </template>
    </div>

    <div class="flex justify-between">
        <button @click="prevQuestion()" :disabled="currentIndex === 0"
            class="bg-main-blue-button border-gray-200 rounded-lg border px-6 py-2 font-bold text-white transition-colors duration-200 hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
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