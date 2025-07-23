<div @click="selectAnswer(key)"
    class="flex cursor-pointer items-center gap-4 rounded-lg border p-2 transition-colors duration-200"
    :class="{
        'bg-main-bg/50 border border-main-bg text-white': answers[currentQuestion.id] ===
            key,
        'bg-white border border-gray-200 hover:border-main-bg': answers[currentQuestion.id] !== key
    }">
    <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-md text-sm font-bold"
        :class="{
            'bg-main-bg text-white': answers[currentQuestion.id] === key,
            'border border-gray-200 bg-white text-black': answers[currentQuestion.id] !== key
        }">
        <span x-text="key"></span>
    </div>
    <p class="text-sm" x-text="text"></p>
</div>
