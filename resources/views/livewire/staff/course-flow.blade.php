<div class="space-y-6">
    {{-- Course Header --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="h-2 bg-gradient-to-r from-teal-500 to-emerald-500" style="background-color: {{ $course->category?->color ?? '#14B8A6' }}"></div>
        <div class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-teal-50 dark:bg-teal-900/30 text-teal-700 dark:text-teal-300">{{ $course->category?->name ?? 'Genel' }}</span>
                        @if($course->is_mandatory)
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400">Zorunlu</span>
                        @endif
                    </div>
                    <h1 class="text-xl font-bold text-gray-800 dark:text-white">{{ $course->title }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $course->description }}</p>
                </div>
                <a href="{{ route('staff.courses.index') }}" class="flex items-center gap-1.5 px-3 py-2 text-sm text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Geri
                </a>
            </div>

            {{-- Course Info Pills --}}
            <div class="flex flex-wrap gap-4 mt-4 text-sm text-gray-500 dark:text-gray-400">
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Sınav: {{ $course->exam_duration_minutes ?? 30 }} dk
                </div>
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Geçme: %{{ $course->passing_score }}
                </div>
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    {{ $course->max_attempts }} deneme hakkı
                </div>
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $course->questions->count() }} soru
                </div>
                @if($course->end_date)
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Son tarih: {{ $course->end_date->format('d.m.Y') }}
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Progress Stepper (only visible during active flow) --}}
    @if(!in_array($step, ['intro', 'completed', 'failed']))
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between max-w-lg mx-auto">
            @foreach($progressSteps as $i => $ps)
                <div class="flex items-center {{ $i < count($progressSteps) - 1 ? 'flex-1' : '' }}">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all
                            @if($ps['status'] === 'completed') bg-emerald-500 text-white
                            @elseif($ps['status'] === 'current') bg-teal-500 text-white ring-4 ring-teal-100 dark:ring-teal-900/50
                            @else bg-gray-200 dark:bg-gray-700 text-gray-400 dark:text-gray-500
                            @endif">
                            @if($ps['status'] === 'completed')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @else
                                {{ $i + 1 }}
                            @endif
                        </div>
                        <span class="text-xs font-medium mt-2 {{ $ps['status'] === 'current' ? 'text-teal-600 dark:text-teal-400' : 'text-gray-400 dark:text-gray-500' }}">{{ $ps['label'] }}</span>
                    </div>
                    @if($i < count($progressSteps) - 1)
                        <div class="flex-1 h-0.5 mx-4 mt-[-20px] {{ $ps['status'] === 'completed' ? 'bg-emerald-400' : 'bg-gray-200 dark:bg-gray-700' }}"></div>
                    @endif
                </div>
            @endforeach
        </div>

        @if($enrollment)
        <div class="text-center mt-3">
            <span class="text-xs text-gray-400 dark:text-gray-500">Deneme {{ $enrollment->current_attempt ?: 1 }} / {{ $course->max_attempts }}</span>
        </div>
        @endif
    </div>
    @endif

    {{-- Step Content --}}
    @if($step === 'intro')
        {{-- Intro / Start --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-8 text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-teal-400 to-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-teal-200 dark:shadow-teal-900/30">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Eğitime Başla</h2>
            <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto mb-6">
                Bu eğitim 3 aşamadan oluşmaktadır: Ön Sınav, Video İzleme ve Son Sınav.
                Başarılı olmanız durumunda sertifikanız otomatik olarak oluşturulacaktır.
            </p>

            <div class="grid grid-cols-3 gap-4 max-w-sm mx-auto mb-8">
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center mx-auto mb-2">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-300">Ön Sınav</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                    <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/50 rounded-lg flex items-center justify-center mx-auto mb-2">
                        <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-300">Video</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                    <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg flex items-center justify-center mx-auto mb-2">
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-300">Son Sınav</p>
                </div>
            </div>

            <button wire:click="startCourse" class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-teal-500 to-emerald-600 text-white font-semibold rounded-xl hover:from-teal-600 hover:to-emerald-700 transition-all shadow-lg shadow-teal-200 dark:shadow-teal-900/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                Eğitime Başla
            </button>
        </div>

    @elseif($step === 'pre_exam')
        @livewire('staff.exam-engine', [
            'enrollmentId' => $enrollment->id,
            'examType' => 'pre_exam',
        ], key('pre-exam-' . ($enrollment->current_attempt ?? 1)))

    @elseif($step === 'video')
        @livewire('staff.video-player', [
            'enrollmentId' => $enrollment->id,
        ], key('video-' . ($enrollment->current_attempt ?? 1)))

    @elseif($step === 'post_exam')
        @livewire('staff.exam-engine', [
            'enrollmentId' => $enrollment->id,
            'examType' => 'post_exam',
        ], key('post-exam-' . ($enrollment->current_attempt ?? 1)))

    @elseif($step === 'result' && $examResult)
        {{-- Result screen with retry option --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-8 text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-amber-200 dark:shadow-amber-900/30">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Sınav Sonucu</h2>
            <p class="text-4xl font-black {{ $examResult['passed'] ? 'text-emerald-500' : 'text-red-500' }} mb-2">%{{ $examResult['score'] }}</p>
            <p class="text-gray-500 dark:text-gray-400 mb-6">{{ $examResult['message'] }}</p>

            @if(!$examResult['passed'] && isset($examResult['attempts_remaining']) && $examResult['attempts_remaining'] > 0)
                <div class="bg-amber-50 dark:bg-amber-900/20 rounded-xl p-4 max-w-sm mx-auto mb-6">
                    <p class="text-sm text-amber-700 dark:text-amber-300">
                        <span class="font-bold">{{ $examResult['attempts_remaining'] }}</span> deneme hakkınız kaldı.
                    </p>
                </div>
                <button wire:click="retryFromBeginning" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-teal-500 to-emerald-600 text-white font-semibold rounded-xl hover:from-teal-600 hover:to-emerald-700 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Tekrar Dene
                </button>
            @endif
        </div>

    @elseif($step === 'completed')
        {{-- Completed / Certificate --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-8 text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-emerald-400 to-green-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-emerald-200 dark:shadow-emerald-900/30">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Tebrikler!</h2>
            <p class="text-gray-500 dark:text-gray-400 mb-2">Bu eğitimi başarıyla tamamladınız.</p>

            @if($enrollment?->certificate)
                <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl p-4 max-w-sm mx-auto mb-6">
                    <p class="text-sm text-emerald-700 dark:text-emerald-300">
                        Sertifika No: <span class="font-bold font-mono">{{ $enrollment->certificate->certificate_number }}</span>
                    </p>
                    <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">
                        Puan: %{{ $enrollment->certificate->final_score }} &middot; {{ $enrollment->certificate->issued_at->format('d.m.Y') }}
                    </p>
                </div>
            @endif

            <a href="{{ route('staff.courses.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-teal-500 to-emerald-600 text-white font-semibold rounded-xl hover:from-teal-600 hover:to-emerald-700 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Eğitimlerime Dön
            </a>
        </div>

    @elseif($step === 'failed')
        {{-- Failed - No attempts left --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-8 text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-red-400 to-rose-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-red-200 dark:shadow-red-900/30">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Eğitim Başarısız</h2>
            <p class="text-gray-500 dark:text-gray-400 mb-6">
                Tüm deneme haklarınız dolmuştur. Lütfen yöneticinizle iletişime geçin.
            </p>
            <a href="{{ route('staff.courses.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Eğitimlerime Dön
            </a>
        </div>
    @endif
</div>
