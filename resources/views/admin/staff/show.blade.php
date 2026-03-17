@extends('layouts.admin')
@section('title', $user->full_name . ' - Personel Detayı')
@section('page-title', 'Personel Detayı')

@section('breadcrumb')
    @include('layouts.partials.breadcrumb', ['items' => [
        ['label' => 'Personel', 'route' => 'admin.staff.index'],
        ['label' => $user->full_name],
    ]])
@endsection

@section('content')
<div class="space-y-6">

    {{-- Profile Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
        <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
        <div class="p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                {{-- Avatar --}}
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center shadow-lg flex-shrink-0">
                    <span class="text-2xl font-bold text-white">{{ strtoupper(substr($user->first_name ?? '', 0, 1) . substr($user->last_name ?? '', 0, 1)) }}</span>
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-1">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->full_name }}</h2>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold {{ $user->is_active ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400' : 'bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                            {{ $user->is_active ? 'Aktif' : 'Pasif' }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->title ?? 'Ünvan belirtilmemiş' }}</p>
                    <div class="flex flex-wrap items-center gap-x-5 gap-y-1 mt-2 text-xs text-gray-500 dark:text-gray-400">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            {{ $user->email }}
                        </span>
                        @if($user->phone)
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            {{ $user->phone }}
                        </span>
                        @endif
                        @if($user->department)
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            {{ $user->department->name }}
                        </span>
                        @endif
                        @if($user->registration_number)
                        <span class="flex items-center gap-1 font-mono">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" /></svg>
                            {{ $user->registration_number }}
                        </span>
                        @endif
                    </div>
                </div>

                {{-- Back Button --}}
                <a href="{{ route('admin.staff.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Listeye Dön
                </a>
            </div>

            {{-- Extra Details --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6 pt-5 border-t border-gray-200 dark:border-gray-700">
                <div>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">İşe Giriş</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white mt-0.5">{{ $user->hire_date?->format('d.m.Y') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Son Giriş</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white mt-0.5">{{ $user->last_login_at?->format('d.m.Y H:i') ?? 'Henüz yok' }}</p>
                </div>
                <div>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Sertifika</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white mt-0.5">{{ $user->certificates_count }}</p>
                </div>
                <div>
                    @php
                        $progressPercent = $user->enrollments_count > 0
                            ? round($user->completed_count / $user->enrollments_count * 100)
                            : 0;
                    @endphp
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">Genel İlerleme</p>
                    <div class="flex items-center gap-2 mt-1">
                        <div class="flex-1 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full rounded-full {{ $progressPercent >= 80 ? 'bg-emerald-500' : ($progressPercent >= 50 ? 'bg-amber-500' : 'bg-primary-500') }}" style="width: {{ $progressPercent }}%"></div>
                        </div>
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">%{{ $progressPercent }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 text-center">
            <p class="text-3xl font-bold text-primary-700 dark:text-primary-300">{{ $user->enrollments_count }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mt-1">Toplam Eğitim</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 text-center">
            <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ $user->completed_count }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mt-1">Başarılı</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 text-center">
            <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $user->failed_count }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mt-1">Başarısız</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 text-center">
            <p class="text-3xl font-bold text-amber-600 dark:text-amber-400">{{ $user->in_progress_count }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mt-1">Devam Eden</p>
        </div>
    </div>

    {{-- Enrollments Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Eğitim Geçmişi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80 dark:bg-gray-700/40 border-b border-gray-200 dark:border-gray-700">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Eğitim</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Kategori</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Ön Sınav</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Son Sınav</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Deneme</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Durum</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Tarih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($user->enrollments as $enrollment)
                        @php
                            $statusClass = match($enrollment->status) {
                                'completed' => 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400',
                                'failed' => 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400',
                                'in_progress' => 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400',
                                default => 'bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-300',
                            };
                            $statusLabel = match($enrollment->status) {
                                'completed' => 'Başarılı',
                                'failed' => 'Başarısız',
                                'in_progress' => 'Devam Ediyor',
                                default => 'Başlamadı',
                            };
                            $preExam = $enrollment->examAttempts->where('exam_type', 'pre_exam')->where('attempt_number', 1)->whereNotNull('finished_at')->first();
                            $postExam = $enrollment->examAttempts->where('exam_type', 'post_exam')->whereNotNull('finished_at')->sortByDesc('attempt_number')->first();
                            $currentAttempt = $enrollment->current_attempt ?: 1;
                            $maxAttempts = $enrollment->course?->max_attempts ?? 3;
                        @endphp
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors">
                            <td class="px-5 py-3">
                                <p class="font-medium text-gray-900 dark:text-white">{{ $enrollment->course?->title ?? '—' }}</p>
                            </td>
                            <td class="px-5 py-3">
                                @if($enrollment->course?->category)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-slate-100 dark:bg-slate-700/60 text-slate-700 dark:text-slate-300">
                                        {{ $enrollment->course->category->name }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center">
                                @if($preExam)
                                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $preExam->score }}</span>
                                    <span class="text-[10px] text-gray-400">/100</span>
                                @else
                                    <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center">
                                @if($postExam)
                                    <span class="text-sm font-semibold {{ $postExam->is_passed ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">{{ $postExam->score }}</span>
                                    <span class="text-[10px] text-gray-400">/100</span>
                                @else
                                    <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center">
                                <span class="text-xs font-medium text-gray-600 dark:text-gray-400">{{ $currentAttempt }}/{{ $maxAttempts }}</span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-5 py-3">
                                <span class="text-xs text-gray-600 dark:text-gray-400">
                                    {{ $enrollment->completed_at?->format('d.m.Y') ?? '—' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Henüz eğitim kaydı bulunmuyor</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Certificates --}}
    @if($user->certificates->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Sertifikalar</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80 dark:bg-gray-700/40 border-b border-gray-200 dark:border-gray-700">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Sertifika No</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Eğitim</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Puan</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Veriliş Tarihi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($user->certificates as $cert)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors">
                            <td class="px-5 py-3">
                                <span class="text-sm font-mono font-medium text-primary-700 dark:text-primary-400">{{ $cert->certificate_number }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <span class="text-sm text-gray-900 dark:text-white">{{ $cert->course?->title ?? '—' }}</span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">{{ $cert->final_score }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <span class="text-xs text-gray-600 dark:text-gray-400">{{ $cert->issued_at?->format('d.m.Y H:i') ?? '—' }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection
