@extends('layouts.staff')
@section('title', 'Sertifikalarım')
@section('page-title', 'Sertifikalarım')

@section('content')
<div class="space-y-6">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg hover:shadow-primary-500/10 transition-all duration-300">
            <div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
            <div class="p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-11 h-11 bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl flex items-center justify-center shadow-md shadow-primary-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    </div>
                    <h3 class="text-[10px] font-bold uppercase tracking-widest text-primary-500/60">Toplam</h3>
                </div>
                <p class="text-3xl font-black text-gray-800 dark:text-white">{{ $stats['total'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">toplam sertifika</p>
            </div>
        </div>

        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg hover:shadow-emerald-500/10 transition-all duration-300">
            <div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
            <div class="p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-11 h-11 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-md shadow-emerald-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-[10px] font-bold uppercase tracking-widest text-emerald-500/60">Bu Yıl</h3>
                </div>
                <p class="text-3xl font-black text-gray-800 dark:text-white">{{ $stats['this_year'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">bu yıl kazanılan</p>
            </div>
        </div>

        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg hover:shadow-amber-500/10 transition-all duration-300">
            <div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-amber-400 to-amber-600"></div>
            <div class="p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-11 h-11 bg-gradient-to-br from-amber-400 to-amber-600 rounded-xl flex items-center justify-center shadow-md shadow-amber-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    </div>
                    <h3 class="text-[10px] font-bold uppercase tracking-widest text-amber-500/60">Ortalama</h3>
                </div>
                <p class="text-3xl font-black text-gray-800 dark:text-white">%{{ $stats['avg_score'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">ortalama başarı puanı</p>
            </div>
        </div>

    </div>

    {{-- Certificate List --}}
    @if($certificates->count() > 0)
    <div class="space-y-3">
        @foreach($certificates as $certificate)
        <div class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg hover:shadow-gray-200/50 dark:hover:shadow-gray-900/50 transition-all duration-300">
            <div class="h-0.5" style="background-color: {{ $certificate->course->category?->color ?? '#14B8A6' }}"></div>
            <div class="p-5 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">

                    {{-- Icon --}}
                    <div class="relative flex-shrink-0">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg"
                             style="background: linear-gradient(135deg, {{ $certificate->course->category?->color ?? '#14B8A6' }}22, {{ $certificate->course->category?->color ?? '#14B8A6' }}44);">
                            <svg class="w-8 h-8" style="color: {{ $certificate->course->category?->color ?? '#14B8A6' }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-emerald-500 rounded-full flex items-center justify-center shadow-sm">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-gray-800 dark:text-white text-sm leading-snug">{{ $certificate->course->title }}</h4>
                        <div class="flex flex-wrap items-center gap-2 mt-1.5">
                            <span class="text-[11px] px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-medium">
                                {{ $certificate->course->category?->name ?? 'Genel' }}
                            </span>
                            <span class="text-[11px] font-mono text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/50 px-2 py-0.5 rounded-lg">
                                {{ $certificate->certificate_number }}
                            </span>
                        </div>
                        <div class="flex items-center gap-4 mt-2">
                            <span class="flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $certificate->issued_at->format('d.m.Y') }}
                            </span>
                            <span class="flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                                <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                Puan: <span class="font-bold text-gray-700 dark:text-gray-300">%{{ $certificate->final_score }}</span>
                            </span>
                        </div>
                    </div>

                    {{-- Score ring + Download --}}
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <div class="relative w-14 h-14">
                            <svg class="w-14 h-14 -rotate-90" viewBox="0 0 56 56">
                                <circle cx="28" cy="28" r="22" fill="none" stroke="currentColor"
                                        class="text-gray-100 dark:text-gray-700" stroke-width="4"/>
                                <circle cx="28" cy="28" r="22" fill="none"
                                        stroke="{{ $certificate->final_score >= 90 ? '#10b981' : ($certificate->final_score >= 70 ? '#14b8a6' : '#f59e0b') }}"
                                        stroke-width="4" stroke-linecap="round"
                                        stroke-dasharray="138.2"
                                        stroke-dashoffset="{{ 138.2 - (138.2 * $certificate->final_score / 100) }}"/>
                            </svg>
                            <span class="absolute inset-0 flex items-center justify-center text-xs font-black text-gray-700 dark:text-white">
                                {{ $certificate->final_score }}
                            </span>
                        </div>

                        <a href="{{ route('staff.certificates.download', $certificate) }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2.5 text-xs font-bold rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 text-white hover:from-primary-700 hover:to-primary-600 shadow-md shadow-primary-500/30 hover:shadow-lg hover:shadow-primary-500/40 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="flex flex-col items-center justify-center bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700 p-14 text-center">
        <div class="w-20 h-20 bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-900/30 rounded-2xl flex items-center justify-center mx-auto mb-5">
            <svg class="w-10 h-10 text-primary-300 dark:text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
        </div>
        <p class="font-bold text-gray-700 dark:text-gray-300 text-base">Henüz sertifikanız yok</p>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1.5 max-w-xs">Eğitimleri başarıyla tamamladığınızda sertifikalarınız burada görünecek.</p>
        <a href="{{ route('staff.courses.index') }}"
           class="inline-flex items-center gap-2 mt-5 px-5 py-2.5 text-sm font-bold rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 text-white shadow-md shadow-primary-500/25 hover:shadow-lg hover:shadow-primary-500/35 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            Eğitimlerime Git
        </a>
    </div>
    @endif

</div>
@endsection
