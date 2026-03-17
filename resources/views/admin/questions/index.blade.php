@extends('layouts.admin')
@section('title', __('lms.exam'))
@section('page-title', __('lms.questions_section'))

@section('breadcrumb')
    @include('layouts.partials.breadcrumb', ['items' => [
        ['label' => 'Eğitimler', 'route' => 'admin.courses.index'],
        ['label' => 'Soru Yönetimi'],
    ]])
@endsection

@section('content')
<div class="space-y-6">
    {{-- Hero Banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-violet-700 via-purple-600 to-indigo-600 shadow-xl shadow-violet-500/20">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-10 -right-10 w-56 h-56 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-14 -left-10 w-72 h-72 bg-indigo-400/10 rounded-full blur-3xl"></div>
        </div>
        <div class="relative px-6 py-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-white/15 backdrop-blur-sm rounded-2xl flex items-center justify-center flex-shrink-0 border border-white/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-black text-white tracking-tight">Soru Yönetimi</h1>
                <p class="text-violet-100 text-sm mt-0.5">Sınav sorularını düzenleyin ve yönetin</p>
            </div>
        </div>
    </div>

    {{-- Coming Soon Card --}}
    <div class="relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="absolute top-0 inset-x-0 h-0.5 bg-gradient-to-r from-violet-400 to-indigo-600"></div>
        <div class="flex flex-col items-center justify-center py-20 px-6 text-center">
            <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-violet-100 to-indigo-200 dark:from-violet-900/40 dark:to-indigo-900/40 flex items-center justify-center mb-5 border-2 border-dashed border-violet-200 dark:border-violet-700">
                <svg class="w-9 h-9 text-violet-500 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2">Soru Yönetimi</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm">Bu sayfa sonraki fazlarda tamamlanacaktır. Soru ekleme ve yönetim paneli yakında kullanıma açılacak.</p>
            <div class="mt-6 flex items-center gap-2.5">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[11px] font-bold bg-violet-50 dark:bg-violet-900/20 text-violet-700 dark:text-violet-400 border border-violet-200 dark:border-violet-800">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Yakında
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
