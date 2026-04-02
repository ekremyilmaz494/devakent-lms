<div wire:poll.30000ms="refreshPending">

    {{-- Flash mesajı --}}
    @if(session('success'))
        <div class="mb-4 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 dark:border-green-800 dark:bg-green-900/20 px-4 py-3">
            <svg class="h-5 w-5 flex-shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <p class="text-sm font-medium text-green-700 dark:text-green-300">{{ session('success') }}</p>
        </div>
    @endif

    @if($errors->has('scores'))
        <div class="mb-4 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 dark:border-red-800 dark:bg-red-900/20 px-4 py-3">
            <svg class="h-5 w-5 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <p class="text-sm font-medium text-red-700 dark:text-red-300">{{ $errors->first('scores') }}</p>
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-6">

        {{-- ─── Sol Panel: Bekleyen Değerlendirmeler ─── --}}
        <div class="lg:w-2/5 flex flex-col gap-4">

            {{-- Başlık --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Bekleyen Değerlendirmeler</h3>
                    @if($pendingCount > 0)
                        <span class="inline-flex items-center justify-center rounded-full bg-amber-500 px-2 py-0.5 text-[11px] font-bold text-white">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Filtreler --}}
            <div class="flex flex-col sm:flex-row gap-2">
                <select wire:model.live="filterCourse"
                    class="flex-1 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-[13px] text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Tüm Kurslar</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                    @endforeach
                </select>
                <select wire:model.live="filterDepartment"
                    class="flex-1 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-[13px] text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Tüm Departmanlar</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Liste --}}
            @if($pendingAttempts->isEmpty())
                <div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 py-16">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 dark:bg-gray-700 mb-3">
                        <svg class="h-7 w-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Bekleyen değerlendirme yok</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Tüm sınavlar değerlendirildi</p>
                </div>
            @else
                <div class="space-y-2">
                    @foreach($pendingAttempts as $attempt)
                        @php
                            $openCount = $attempt->answers()
                                ->whereHas('question', fn($q) => $q->where('question_type', 'open_ended'))
                                ->count();
                            $isSelected = $selectedAttemptId === $attempt->id;
                        @endphp
                        <button wire:click="selectAttempt({{ $attempt->id }})"
                            class="w-full text-left rounded-xl border px-4 py-3 transition-all duration-150
                                   {{ $isSelected
                                       ? 'border-amber-400 bg-amber-50 dark:bg-amber-900/20 ring-1 ring-amber-400'
                                       : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-amber-300 hover:bg-amber-50 dark:hover:bg-amber-900/10' }}">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="text-[13px] font-semibold text-gray-800 dark:text-gray-200 truncate">
                                        {{ $attempt->enrollment->user->full_name }}
                                    </p>
                                    <p class="text-[12px] text-gray-500 dark:text-gray-400 truncate mt-0.5">
                                        {{ $attempt->enrollment->course->title }}
                                    </p>
                                    <div class="flex items-center gap-3 mt-1.5">
                                        <span class="text-[11px] text-gray-400 dark:text-gray-500">
                                            {{ $attempt->finished_at?->format('d.m.Y H:i') }}
                                        </span>
                                        @if($attempt->enrollment->user->department)
                                            <span class="text-[11px] text-gray-400 dark:text-gray-500">
                                                · {{ $attempt->enrollment->user->department->name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <span class="flex-shrink-0 inline-flex items-center gap-1 rounded-full bg-amber-100 dark:bg-amber-900/30 px-2 py-0.5 text-[11px] font-semibold text-amber-700 dark:text-amber-300">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    {{ $openCount }} soru
                                </span>
                            </div>
                        </button>
                    @endforeach
                </div>

                <div class="mt-2">
                    {{ $pendingAttempts->links() }}
                </div>
            @endif
        </div>

        {{-- ─── Sağ Panel: Değerlendirme Formu ─── --}}
        <div class="lg:w-3/5">
            @if(!$selectedAttempt)
                <div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 h-full min-h-[400px]">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 dark:bg-gray-700 mb-3">
                        <svg class="h-7 w-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Değerlendirmek için sol listeden bir sınav seçin</p>
                </div>
            @else
                <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden">

                    {{-- Öğrenci Bilgi Kartı --}}
                    <div class="border-b border-gray-100 dark:border-gray-700 bg-amber-50 dark:bg-amber-900/10 px-5 py-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-amber-500 text-white text-sm font-bold flex-shrink-0">
                                        {{ strtoupper(substr($selectedAttempt->enrollment->user->first_name ?? 'U', 0, 1) . substr($selectedAttempt->enrollment->user->last_name ?? 'K', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-[14px] font-semibold text-gray-800 dark:text-gray-100">
                                            {{ $selectedAttempt->enrollment->user->full_name }}
                                        </p>
                                        <p class="text-[12px] text-gray-500 dark:text-gray-400">
                                            {{ $selectedAttempt->enrollment->user->department?->name ?? 'Departman yok' }}
                                        </p>
                                    </div>
                                </div>
                                <p class="text-[13px] font-medium text-gray-700 dark:text-gray-300 mt-2">
                                    {{ $selectedAttempt->enrollment->course->title }}
                                </p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-[11px] text-gray-400 dark:text-gray-500 mb-1">Otomatik Puan</p>
                                <p class="text-2xl font-black text-amber-600 dark:text-amber-400">{{ $selectedAttempt->score }}</p>
                                <p class="text-[11px] text-gray-400 dark:text-gray-500">{{ $selectedAttempt->finished_at?->format('d.m.Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Cevaplar Listesi --}}
                    <div class="divide-y divide-gray-100 dark:divide-gray-700 max-h-[500px] overflow-y-auto">
                        @foreach($selectedAttempt->answers->sortBy('question.sort_order') as $answer)
                            @php $q = $answer->question; @endphp
                            <div class="px-5 py-4">

                                {{-- Soru Metni --}}
                                <div class="flex items-start gap-2 mb-3">
                                    <span class="flex-shrink-0 inline-flex items-center justify-center w-5 h-5 rounded-full text-[11px] font-bold
                                        {{ $q->isOpenEnded()
                                            ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300'
                                            : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                                        {{ $loop->iteration }}
                                    </span>
                                    <div class="flex-1">
                                        <p class="text-[13px] font-medium text-gray-800 dark:text-gray-200">{{ $q->question_text }}</p>
                                        <span class="inline-block mt-1 text-[10px] font-semibold uppercase tracking-wide
                                            {{ $q->isOpenEnded()
                                                ? 'text-amber-600 dark:text-amber-400'
                                                : 'text-gray-400 dark:text-gray-500' }}">
                                            {{ $q->isOpenEnded() ? 'Açık Uçlu' : ($q->isTrueFalse() ? 'Doğru/Yanlış' : 'Çoktan Seçmeli') }}
                                        </span>
                                    </div>
                                </div>

                                @if($q->isOpenEnded())
                                    {{-- Açık Uçlu: Öğrenci Cevabı + Puanlama --}}
                                    <div class="ml-7 space-y-3">
                                        <div class="rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 px-3 py-2">
                                            <p class="text-[11px] font-semibold text-gray-400 dark:text-gray-500 mb-1">Öğrenci Cevabı</p>
                                            <p class="text-[13px] text-gray-700 dark:text-gray-300">
                                                {{ $answer->text_answer ?: '(Boş bırakıldı)' }}
                                            </p>
                                        </div>
                                        <div class="flex gap-3">
                                            <div class="w-28">
                                                <label class="block text-[11px] font-semibold text-gray-500 dark:text-gray-400 mb-1">Puan (0-10)</label>
                                                <input type="number" min="0" max="10" step="0.5"
                                                    wire:model="scores.{{ $answer->id }}"
                                                    class="w-full rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-1.5 text-[13px] text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                                    placeholder="0-10">
                                            </div>
                                            <div class="flex-1">
                                                <label class="block text-[11px] font-semibold text-gray-500 dark:text-gray-400 mb-1">Geri Bildirim (isteğe bağlı)</label>
                                                <textarea wire:model="feedbacks.{{ $answer->id }}"
                                                    rows="2"
                                                    class="w-full rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-1.5 text-[13px] text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-500 resize-none"
                                                    placeholder="Öğrenciye geri bildirim..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    {{-- Otomatik Puanlanan: Sadece Görüntüle --}}
                                    <div class="ml-7 flex items-center gap-3">
                                        <span class="text-[13px] text-gray-600 dark:text-gray-400">
                                            Seçilen:
                                            <span class="font-semibold text-gray-800 dark:text-gray-200 uppercase">
                                                {{ $answer->selected_option ?? '(Boş)' }}
                                            </span>
                                        </span>
                                        @if($answer->is_correct === true)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-green-100 dark:bg-green-900/30 px-2 py-0.5 text-[11px] font-semibold text-green-700 dark:text-green-300">
                                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                                Doğru
                                            </span>
                                        @elseif($answer->is_correct === false)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-red-100 dark:bg-red-900/30 px-2 py-0.5 text-[11px] font-semibold text-red-700 dark:text-red-300">
                                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                                                Yanlış
                                            </span>
                                        @endif
                                        @if($q->correct_option)
                                            <span class="text-[12px] text-gray-400 dark:text-gray-500">
                                                Doğru cevap: <span class="font-semibold uppercase">{{ $q->correct_option }}</span>
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Alt Butonlar --}}
                    <div class="border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 px-5 py-4 flex items-center justify-between gap-3">
                        <button wire:click="resetSelection"
                            class="rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-[13px] font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            ← Geri
                        </button>
                        <button wire:click="submitGrades"
                            wire:loading.attr="disabled"
                            wire:target="submitGrades"
                            class="inline-flex items-center gap-2 rounded-lg bg-amber-500 hover:bg-amber-600 disabled:opacity-60 px-5 py-2 text-[13px] font-semibold text-white transition-colors shadow-sm shadow-amber-500/20">
                            <span wire:loading.remove wire:target="submitGrades">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                            <span wire:loading wire:target="submitGrades">
                                <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                            </span>
                            <span wire:loading.remove wire:target="submitGrades">Değerlendirmeyi Tamamla</span>
                            <span wire:loading wire:target="submitGrades">Kaydediliyor...</span>
                        </button>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
