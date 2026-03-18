<div>
    <button wire:click="openDetail" class="inline-flex items-center gap-1 text-xs text-primary-500 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 font-medium transition-colors">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        Detay
    </button>

    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-3xl max-h-[85vh] flex flex-col">
            {{-- Header --}}
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Sınav Detayı</h3>
                    <p class="text-xs text-gray-500">{{ $attempt->enrollment->course->title ?? '' }} — {{ $attempt->exam_type === 'pre_exam' ? 'Ön Sınav' : 'Son Sınav' }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-xl font-bold {{ $attempt->is_passed ? 'text-emerald-500' : 'text-red-500' }}">{{ $attempt->score }}/100</p>
                        <p class="text-[11px] text-gray-500">{{ $attempt->correct_answers }}/{{ $attempt->total_questions }} doğru</p>
                    </div>
                    <button wire:click="$set('showModal', false)" class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Questions --}}
            <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4">
                @foreach($answers as $i => $answer)
                @php
                    $qType = $answer['question_type'] ?? 'multiple_choice';
                    $isOpenEnded = $qType === 'open_ended';
                    $borderClass = $isOpenEnded
                        ? 'border-amber-200 dark:border-amber-800 bg-amber-50/30 dark:bg-amber-900/5'
                        : ($answer['is_correct'] ? 'border-emerald-200 dark:border-emerald-800 bg-emerald-50/50 dark:bg-emerald-900/10' : 'border-red-200 dark:border-red-800 bg-red-50/50 dark:bg-red-900/10');
                    $numClass = $isOpenEnded
                        ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400'
                        : ($answer['is_correct'] ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400');
                @endphp
                    <div class="rounded-xl border {{ $borderClass }} p-4">
                        <div class="flex items-start gap-3 mb-3">
                            <span class="flex-shrink-0 w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold {{ $numClass }}">{{ $i + 1 }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $answer['question_text'] }}</p>
                                @if($isOpenEnded)
                                    <span class="text-[10px] font-medium text-amber-600 dark:text-amber-400 mt-0.5 inline-block">Açık Uçlu — Eğitmen Değerlendirmesi</span>
                                @endif
                            </div>
                        </div>

                        @if($isOpenEnded)
                            <div class="ml-10">
                                @if($answer['text_answer'])
                                    <div class="p-3 bg-white dark:bg-gray-700/50 rounded-lg border border-amber-200 dark:border-amber-800/40 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $answer['text_answer'] }}
                                    </div>
                                @else
                                    <p class="text-xs text-gray-400 dark:text-gray-500 italic">Cevap verilmedi.</p>
                                @endif
                            </div>
                        @else
                            <div class="ml-10 space-y-1.5">
                                @foreach(['a', 'b', 'c', 'd'] as $opt)
                                    @if(!empty($answer['options'][$opt]))
                                    @php
                                        $isSelected = $answer['selected_option'] === $opt;
                                        $isCorrectOpt = $answer['correct_option'] === $opt;
                                    @endphp
                                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-[13px]
                                        @if($isCorrectOpt) bg-emerald-100 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 font-medium
                                        @elseif($isSelected && !$isCorrectOpt) bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-400 line-through
                                        @else text-gray-600 dark:text-gray-400
                                        @endif">
                                        <span class="w-5 h-5 rounded flex items-center justify-center text-[10px] font-bold flex-shrink-0
                                            @if($isCorrectOpt) bg-emerald-500 text-white
                                            @elseif($isSelected && !$isCorrectOpt) bg-red-500 text-white
                                            @else bg-gray-200 dark:bg-gray-700 text-gray-500
                                            @endif">{{ strtoupper($opt) }}</span>
                                        <span>{{ $answer['options'][$opt] }}</span>
                                        @if($isCorrectOpt)
                                            <svg class="w-4 h-4 text-emerald-500 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        @elseif($isSelected && !$isCorrectOpt)
                                            <svg class="w-4 h-4 text-red-500 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        @endif
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
