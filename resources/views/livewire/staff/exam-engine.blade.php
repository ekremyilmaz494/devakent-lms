<div x-data="{
    timeRemaining: @js($timeRemaining),
    isFinished: @entangle('isFinished'),
    showConfirmModal: false,
    timerInterval: null,
    init() {
        this.startTimer();
    },
    startTimer() {
        this.timerInterval = setInterval(() => {
            if (this.isFinished) {
                clearInterval(this.timerInterval);
                return;
            }
            this.timeRemaining--;
            if (this.timeRemaining <= 0) {
                clearInterval(this.timerInterval);
                $wire.timeUp();
            }
        }, 1000);
    },
    formatTime(seconds) {
        const m = Math.floor(seconds / 60);
        const s = seconds % 60;
        return String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
    }
}" x-init="init()" class="space-y-4">

    {{-- Exam Header --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-3 md:p-4">
        <div class="flex items-center justify-between gap-2">
            <div class="flex items-center gap-2 md:gap-3 min-w-0">
                <div class="w-8 h-8 md:w-10 md:h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $examType === 'pre_exam' ? 'bg-primary-100 dark:bg-primary-900/30' : 'bg-emerald-100 dark:bg-emerald-900/30' }}">
                    <svg class="w-4 h-4 md:w-5 md:h-5 {{ $examType === 'pre_exam' ? 'text-primary-600 dark:text-primary-400' : 'text-emerald-600 dark:text-emerald-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div class="min-w-0">
                    <h2 class="font-bold text-sm md:text-base text-gray-800 dark:text-white truncate">{{ $examType === 'pre_exam' ? 'Ön Sınav' : 'Son Sınav' }}</h2>
                    <p class="text-[11px] md:text-xs text-gray-600 dark:text-gray-400">{{ $answeredCount }}/{{ $totalQuestions }} cevaplandı</p>
                </div>
            </div>

            {{-- Timer --}}
            <div class="flex items-center gap-1.5 md:gap-2 px-2.5 md:px-4 py-1.5 md:py-2 rounded-xl flex-shrink-0" :class="timeRemaining <= 60 ? 'bg-red-100 dark:bg-red-900/30' : 'bg-gray-50 dark:bg-gray-700/50'">
                <svg class="w-4 h-4 md:w-5 md:h-5" :class="timeRemaining <= 60 ? 'text-red-500 animate-pulse' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-sm md:text-lg font-mono font-bold" :class="timeRemaining <= 60 ? 'text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-200'" x-text="formatTime(timeRemaining)"></span>
            </div>
        </div>

        {{-- Progress Bar --}}
        <div class="mt-3 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-primary-500 to-primary-600 rounded-full transition-all duration-300"
                 style="width: {{ $totalQuestions > 0 ? ($answeredCount / $totalQuestions) * 100 : 0 }}%"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3 md:gap-4">
        {{-- Question Content --}}
        <div class="lg:col-span-3 order-2 lg:order-1">
            @if($currentQuestion && !$isFinished)
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 md:p-6">
                {{-- Question Number --}}
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-xs font-bold text-primary-600 dark:text-primary-400 bg-primary-100 dark:bg-primary-900/40 px-2.5 py-1 rounded-full">Soru {{ $currentQuestionIndex + 1 }}/{{ $totalQuestions }}</span>
                </div>

                {{-- Question Text --}}
                <h3 class="text-base md:text-lg font-semibold text-gray-800 dark:text-white mb-4 md:mb-6 leading-relaxed">{{ $currentQuestion['question_text'] }}</h3>

                {{-- Options — soru tipine göre --}}
                @if($currentQuestion['question_type'] === 'multiple_choice')
                    <div class="space-y-2 md:space-y-3">
                        @foreach(['a' => 'option_a', 'b' => 'option_b', 'c' => 'option_c', 'd' => 'option_d'] as $key => $field)
                            @if(!empty($currentQuestion[$field]))
                            @php $isSelected = isset($answers[$currentQuestion['id']]) && $answers[$currentQuestion['id']] === $key; @endphp
                            <button wire:click="selectAnswer('{{ $key }}')"
                                class="w-full flex items-center gap-3 md:gap-4 p-3 md:p-4 rounded-xl border-2 text-left transition-all duration-200
                                {{ $isSelected ? 'border-primary-500 bg-primary-100 dark:bg-primary-900/30 dark:border-primary-400' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                                <div class="w-8 h-8 md:w-9 md:h-9 rounded-lg flex items-center justify-center flex-shrink-0 text-xs md:text-sm font-bold transition-colors
                                    {{ $isSelected ? 'bg-primary-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }}">
                                    {{ strtoupper($key) }}
                                </div>
                                <span class="text-sm md:text-[15px] {{ $isSelected ? 'text-primary-700 dark:text-primary-300 font-medium' : 'text-gray-700 dark:text-gray-300' }}">{{ $currentQuestion[$field] }}</span>
                            </button>
                            @endif
                        @endforeach
                    </div>

                @elseif($currentQuestion['question_type'] === 'true_false')
                    <div class="grid grid-cols-2 gap-3">
                        @php
                            $tfSelected = $answers[$currentQuestion['id']] ?? null;
                        @endphp
                        <button wire:click="selectAnswer('a')"
                            class="flex flex-col items-center justify-center gap-2 p-4 md:p-6 rounded-xl border-2 transition-all duration-200
                            {{ $tfSelected === 'a' ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20 dark:border-emerald-400' : 'border-gray-200 dark:border-gray-700 hover:border-emerald-300 dark:hover:border-emerald-700 hover:bg-emerald-50/50 dark:hover:bg-emerald-900/10' }}">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $tfSelected === 'a' ? 'bg-emerald-500' : 'bg-gray-100 dark:bg-gray-700' }}">
                                <svg class="w-6 h-6 {{ $tfSelected === 'a' ? 'text-white' : 'text-gray-400 dark:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="font-bold text-sm md:text-base {{ $tfSelected === 'a' ? 'text-emerald-700 dark:text-emerald-300' : 'text-gray-600 dark:text-gray-400' }}">Doğru</span>
                        </button>
                        <button wire:click="selectAnswer('b')"
                            class="flex flex-col items-center justify-center gap-2 p-4 md:p-6 rounded-xl border-2 transition-all duration-200
                            {{ $tfSelected === 'b' ? 'border-red-500 bg-red-50 dark:bg-red-900/20 dark:border-red-400' : 'border-gray-200 dark:border-gray-700 hover:border-red-300 dark:hover:border-red-700 hover:bg-red-50/50 dark:hover:bg-red-900/10' }}">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $tfSelected === 'b' ? 'bg-red-500' : 'bg-gray-100 dark:bg-gray-700' }}">
                                <svg class="w-6 h-6 {{ $tfSelected === 'b' ? 'text-white' : 'text-gray-400 dark:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </div>
                            <span class="font-bold text-sm md:text-base {{ $tfSelected === 'b' ? 'text-red-700 dark:text-red-300' : 'text-gray-600 dark:text-gray-400' }}">Yanlış</span>
                        </button>
                    </div>

                @elseif($currentQuestion['question_type'] === 'open_ended')
                    @php $currentText = $answers[$currentQuestion['id']] ?? ''; @endphp
                    <div x-data="{ text: @js($currentText) }" class="space-y-2">
                        <textarea
                            x-model="text"
                            @blur="$wire.saveTextAnswer(text)"
                            rows="5"
                            placeholder="Cevabınızı buraya yazınız..."
                            class="w-full px-4 py-3 text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-400 focus:border-primary-400 dark:focus:ring-primary-500 dark:focus:border-primary-500 resize-none transition-colors placeholder-gray-400 dark:placeholder-gray-500"
                        ></textarea>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Açık uçlu sorular eğitmen tarafından değerlendirilir. Alandan ayrıldığınızda otomatik kaydedilir.
                        </p>
                    </div>
                @endif

                {{-- Navigation --}}
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2 mt-4 md:mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button wire:click="previousQuestion"
                        @if($currentQuestionIndex === 0) disabled @endif
                        class="flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors disabled:opacity-40 disabled:cursor-not-allowed w-full sm:w-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Önceki
                    </button>

                    @if($currentQuestionIndex < $totalQuestions - 1)
                        <button wire:click="nextQuestion"
                            class="flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-primary-600 dark:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg transition-colors w-full sm:w-auto">
                            Sonraki
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    @else
                        <button @click="showConfirmModal = true"
                            class="flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold bg-gradient-to-r from-primary-500 to-primary-700 text-white rounded-lg hover:from-primary-600 hover:to-primary-800 transition-all shadow-sm w-full sm:w-auto">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Sınavı Bitir
                        </button>
                    @endif
                </div>
            </div>

            @elseif($isFinished && $result)
            {{-- Result Display --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 md:p-8 text-center">
                <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 md:mb-6 shadow-lg
                    {{ $result['passed'] ? 'bg-gradient-to-br from-emerald-400 to-green-500 shadow-emerald-200 dark:shadow-emerald-900/30' : 'bg-gradient-to-br from-red-400 to-rose-500 shadow-red-200 dark:shadow-red-900/30' }}">
                    @if($result['passed'])
                        <svg class="w-8 h-8 md:w-10 md:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @else
                        <svg class="w-8 h-8 md:w-10 md:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @endif
                </div>

                <h2 class="text-lg md:text-xl font-bold text-gray-800 dark:text-white mb-1">
                    {{ $examType === 'pre_exam' ? 'Ön Sınav Tamamlandı' : 'Son Sınav Sonucu' }}
                </h2>
                <p class="text-3xl md:text-4xl font-black mb-2 {{ $result['passed'] ? 'text-emerald-500' : 'text-red-500' }}">%{{ $result['score'] }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ $result['message'] }}</p>

                @if($examType === 'pre_exam')
                    <p class="text-xs text-gray-400 dark:text-gray-500">Video izleme aşamasına otomatik olarak geçilecek...</p>
                @endif
            </div>
            @endif
        </div>

        {{-- Question Navigator Sidebar --}}
        <div class="lg:col-span-1 order-1 lg:order-2">
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-3 md:p-4 sticky top-4">
                <h4 class="text-xs md:text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 md:mb-3">Soru Navigasyonu</h4>
                <div class="grid grid-cols-8 sm:grid-cols-10 lg:grid-cols-5 gap-1.5 md:gap-2">
                    @foreach($questions as $i => $q)
                        @php
                            $isAnswered = isset($answers[$q['id']]);
                            $isCurrent = $i === $currentQuestionIndex;
                        @endphp
                        <button wire:click="goToQuestion({{ $i }})"
                            class="w-full aspect-square rounded-lg text-[11px] md:text-xs font-bold flex items-center justify-center transition-all
                            @if($isCurrent) ring-2 ring-primary-500 bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300
                            @elseif($isAnswered) bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400
                            @else bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600
                            @endif">
                            {{ $i + 1 }}
                        </button>
                    @endforeach
                </div>

                <div class="mt-3 md:mt-4 pt-2 md:pt-3 border-t border-gray-200 dark:border-gray-700 flex lg:flex-col gap-3 lg:gap-2 text-[11px] md:text-xs text-gray-600 dark:text-gray-400">
                    <div class="flex items-center gap-1.5 md:gap-2">
                        <div class="w-2.5 h-2.5 md:w-3 md:h-3 rounded bg-emerald-100 dark:bg-emerald-900/30 flex-shrink-0"></div>
                        Cevaplandı ({{ $answeredCount }})
                    </div>
                    <div class="flex items-center gap-1.5 md:gap-2">
                        <div class="w-2.5 h-2.5 md:w-3 md:h-3 rounded bg-gray-100 dark:bg-gray-700 flex-shrink-0"></div>
                        Boş ({{ $totalQuestions - $answeredCount }})
                    </div>
                </div>

                @if(!$isFinished)
                <button @click="showConfirmModal = true"
                    class="w-full mt-4 px-4 py-2.5 text-sm font-semibold bg-gradient-to-r from-primary-500 to-primary-700 text-white rounded-xl hover:from-primary-600 hover:to-primary-800 transition-all">
                    Sınavı Bitir
                </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Confirm Submit Modal --}}
    <div x-show="showConfirmModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="showConfirmModal = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-5 md:p-6 max-w-sm w-full mx-4"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2">
            <div class="text-center">
                <div class="w-14 h-14 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-1">Sınavı Bitir?</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                    {{ $answeredCount }}/{{ $totalQuestions }} soruyu cevapladınız.
                </p>
                @if($answeredCount < $totalQuestions)
                    <p class="text-xs text-amber-600 dark:text-amber-400 mb-4">
                        {{ $totalQuestions - $answeredCount }} soru boş bırakılacak!
                    </p>
                @else
                    <p class="text-xs text-emerald-600 dark:text-emerald-400 mb-4">
                        Tüm soruları cevapladınız.
                    </p>
                @endif

                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <button @click="showConfirmModal = false" class="w-full sm:flex-1 px-4 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        Geri Dön
                    </button>
                    <button wire:click="submitExam" @click="showConfirmModal = false"
                        class="w-full sm:flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-primary-500 to-primary-700 rounded-xl hover:from-primary-600 hover:to-primary-800 transition-all">
                        Bitir
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
