{{-- resources/views/components/pilihan-jawaban.blade.php --}}
<div @click="selectAnswer(key)"
    class="flex cursor-pointer items-center gap-4 rounded-lg border p-2 transition-colors duration-200"
    :class="{
        'bg-ujian-green-bg bg-opacity-50 border border-ujian-green-border text-white': answers[currentQuestion.id] ===
            key,
        'bg-white border-ujian-gray-200 hover:border-ujian-blue': answers[currentQuestion.id] !== key
    }">
    <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-md text-sm font-bold"
        :class="{
            'bg-ujian-green-border text-white': answers[currentQuestion.id] === key,
            'bg-ujian-gray-100 text-black': answers[currentQuestion.id] !== key
        }">
        <span x-text="key"></span>
    </div>
    <p class="text-sm" x-text="text"></p>
</div>
