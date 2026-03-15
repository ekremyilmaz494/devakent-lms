<div x-data="{
    timeRemaining: @entangle('timeRemaining'),
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
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $examType === 'pre_exam' ? 'bg-blue-100 dark:bg-blue-900/30' : 'bg-emerald-100 dark:bg-emerald-900/30' }}">
                    <svg class="w-5 h-5 {{ $examType === 'pre_exam' ? 'text-blue-600 dark:text-blue-400' : 'text-emerald-600 dark:text-emerald-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div>
                    <h2 class="font-bold text-gray-800 dark:text-white">{{ $examType === 'pre_exam' ? 'Ön Sınav' : 'Son Sınav' }}</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $answeredCount }}/{{ $totalQuestions }} cevaplandı</p>
                </div>
            </div>

            {{-- Timer --}}
            <div class="flex items-center gap-2 px-4 py-2 rounded-xl" :class="timeRemaining <= 60 ? 'bg-red-50 dark:bg-red-900/20' : 'bg-gray-50 dark:bg-gray-700/50'">
                <svg class="w-5 h-5" :class="timeRemaining <= 60 ? 'text-red-500 animate-pulse' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-lg font-mono font-bold" :class="timeRemaining <= 60 ? 'text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-200'" x-text="formatTime(timeRemaining)"></span>
            </div>
        </div>

        {{-- Progress Bar --}}
        <div class="mt-3 h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-teal-500 to-emerald-500 rounded-full transition-all duration-300"
                 style="width: {{ $totalQuestions > 0 ? ($answeredCount / $totalQuestions) * 100 : 0 }}%"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
        {{-- Question Content --}}
        <div class="lg:col-span-3">
            @if($currentQuestion && !$isFinished)
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                {{-- Question Number --}}
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-xs font-bold text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-900/30 px-2.5 py-1 rounded-full">Soru {{ $currentQuestionIndex + 1 }}/{{ $totalQuestions }}</span>
                </div>

                {{-- Question Text --}}
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-6 leading-relaxed">{{ $currentQuestion['question_text'] }}</h3>

                {{-- Options --}}
                <div class="space-y-3">
                    @foreach(['a' => 'option_a', 'b' => 'option_b', 'c' => 'option_c', 'd' => 'option_d'] as $key => $field)
                        @php
                            $isSelected = isset($answers[$currentQuestion['id']]) && $answers[$currentQuestion['id']] === $key;
                        @endphp
                        <button wire:click="selectAnswer('{{ $key }}')"
                            class="w-full flex items-center gap-4 p-4 rounded-xl border-2 text-left transition-all duration-200
                            {{ $isSelected
                                ? 'border-teal-500 bg-teal-50 dark:bg-teal-900/20 dark:border-teal-400'
                                : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 text-sm font-bold transition-colors
                                {{ $isSelected
                                    ? 'bg-teal-500 text-white'
                                    : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                                {{ strtoupper($key) }}
                            </div>
                            <span class="text-[15px] {{ $isSelected ? 'text-teal-700 dark:text-teal-300 font-medium' : 'text-gray-700 dark:text-gray-300' }}">{{ $currentQuestion[$field] }}</span>
                        </button>
                    @endforeach
                </div>

                {{-- Navigation --}}
                <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <button wire:click="previousQuestion"
                        @if($currentQuestionIndex === 0) disabled @endif
                        class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Önceki
                    </button>

                    @if($currentQuestionIndex < $totalQuestions - 1)
                        <button wire:click="nextQuestion"
                            class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-teal-600 dark:text-teal-400 hover:bg-teal-50 dark:hover:bg-teal-900/20 rounded-lg transition-colors">
                            Sonraki
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    @else
                        <button @click="showConfirmModal = true"
                            class="flex items-center gap-2 px-5 py-2.5 text-sm font-semibold bg-gradient-to-r from-teal-500 to-emerald-600 text-white rounded-lg hover:from-teal-600 hover:to-emerald-700 transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Sınavı Bitir
                        </button>
                    @endif
                </div>
            </div>

            @elseif($isFinished && $result)
            {{-- Result Display --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-8 text-center">
                <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg
                    {{ $result['passed'] ? 'bg-gradient-to-br from-emerald-400 to-green-500 shadow-emerald-200 dark:shadow-emerald-900/30' : 'bg-gradient-to-br from-red-400 to-rose-500 shadow-red-200 dark:shadow-red-900/30' }}">
                    @if($result['passed'])
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @else
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @endif
                </div>

                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-1">
                    {{ $examType === 'pre_exam' ? 'Ön Sınav Tamamlandı' : 'Son Sınav Sonucu' }}
                </h2>
                <p class="text-4xl font-black mb-2 {{ $result['passed'] ? 'text-emerald-500' : 'text-red-500' }}">%{{ $result['score'] }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ $result['message'] }}</p>

                @if($examType === 'pre_exam')
                    <p class="text-xs text-gray-400 dark:text-gray-500">Video izleme aşamasına otomatik olarak geçilecek...</p>
                @endif
            </div>
            @endif
        </div>

        {{-- Question Navigator Sidebar --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 sticky top-4">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Soru Navigasyonu</h4>
                <div class="grid grid-cols-5 gap-2">
                    @foreach($questions as $i => $q)
                        @php
                            $isAnswered = isset($answers[$q['id']]);
                            $isCurrent = $i === $currentQuestionIndex;
                        @endphp
                        <button wire:click="goToQuestion({{ $i }})"
                            class="w-full aspect-square rounded-lg text-xs font-bold flex items-center justify-center transition-all
                            @if($isCurrent) ring-2 ring-teal-500 bg-teal-50 dark:bg-teal-900/30 text-teal-700 dark:text-teal-300
                            @elseif($isAnswered) bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400
                            @else bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600
                            @endif">
                            {{ $i + 1 }}
                        </button>
                    @endforeach
                </div>

                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 space-y-2 text-xs text-gray-500 dark:text-gray-400">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded bg-emerald-100 dark:bg-emerald-900/30"></div>
                        Cevaplandı ({{ $answeredCount }})
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded bg-gray-100 dark:bg-gray-700"></div>
                        Boş ({{ $totalQuestions - $answeredCount }})
                    </div>
                </div>

                @if(!$isFinished)
                <button @click="showConfirmModal = true"
                    class="w-full mt-4 px-4 py-2.5 text-sm font-semibold bg-gradient-to-r from-teal-500 to-emerald-600 text-white rounded-xl hover:from-teal-600 hover:to-emerald-700 transition-all">
                    Sınavı Bitir
                </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Confirm Submit Modal --}}
    <div x-show="showConfirmModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="showConfirmModal = false"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 max-w-sm w-full" x-transition>
            <div class="text-center">
                <div class="w-14 h-14 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-1">Sınavı Bitir?</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">
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

                <div class="flex gap-3">
                    <button @click="showConfirmModal = false" class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        Geri Dön
                    </button>
                    <button wire:click="submitExam" @click="showConfirmModal = false"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-teal-500 to-emerald-600 rounded-xl hover:from-teal-600 hover:to-emerald-700 transition-all">
                        Bitir
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
