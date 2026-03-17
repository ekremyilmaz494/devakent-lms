@extends('layouts.staff')
@section('title', 'Profilim')
@section('page-title', 'Profilim')

@section('content')
<div class="space-y-6">

    {{-- Profile Hero Card --}}
    <div class="relative overflow-hidden bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
        {{-- Top gradient bar --}}
        <div class="h-24 bg-gradient-to-r from-primary-600 via-primary-500 to-teal-500 relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-32 h-32 rounded-full bg-white/10 blur-xl"></div>
            <div class="absolute left-1/3 bottom-0 w-20 h-20 rounded-full bg-white/10 blur-lg"></div>
        </div>

        <div class="px-6 pb-6">
            {{-- Avatar --}}
            <div class="flex items-end gap-4">
                <div class="relative flex-shrink-0 -mt-10 sm:-mt-12">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl ring-4 ring-white dark:ring-gray-800 overflow-hidden bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center shadow-lg">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->full_name }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-3xl font-black text-primary-600 dark:text-primary-400">
                                {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                            </span>
                        @endif
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-emerald-500 rounded-full border-2 border-white dark:border-gray-800 flex items-center justify-center">
                        <div class="w-2 h-2 rounded-full bg-white"></div>
                    </div>
                </div>

                <div class="pb-2 sm:pl-1">
                    <h2 class="text-xl font-black text-gray-800 dark:text-white">{{ $user->full_name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                        {{ $user->title ?? 'Personel' }}
                        @if($user->department)
                            <span class="text-gray-300 dark:text-gray-600 mx-1">&middot;</span>
                            <span class="text-primary-600 dark:text-primary-400 font-medium">{{ $user->department->name }}</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- Meta chips --}}
            <div class="flex flex-wrap items-center gap-2 mt-4">
                <span class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 px-3 py-1.5 rounded-xl">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ $user->email }}
                </span>
                @if($user->phone)
                <span class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 px-3 py-1.5 rounded-xl">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    {{ $user->phone }}
                </span>
                @endif
                @if($user->registration_number)
                <span class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 px-3 py-1.5 rounded-xl font-mono">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                    {{ $user->registration_number }}
                </span>
                @endif
                @if($user->hire_date)
                <span class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 px-3 py-1.5 rounded-xl">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    İşe giriş: {{ $user->hire_date->format('d.m.Y') }}
                </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Forms Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Personal Information --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white">Kişisel Bilgiler</h3>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500">Ad, soyad ve iletişim bilgilerinizi güncelleyin</p>
                </div>
            </div>

            <div class="p-5">
                @if(session('success_profile'))
                <div class="mb-4 flex items-center gap-2 px-3 py-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 text-xs text-emerald-700 dark:text-emerald-400 font-medium">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success_profile') }}
                </div>
                @endif

                <form action="{{ route('staff.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="first_name" class="block text-[11px] font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1.5">Ad</label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                                       autocomplete="given-name"
                                       class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-gray-800 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-primary-500/30 focus:border-primary-500 transition-all outline-none">
                                @error('first_name') <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="last_name" class="block text-[11px] font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1.5">Soyad</label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                                       autocomplete="family-name"
                                       class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-gray-800 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-primary-500/30 focus:border-primary-500 transition-all outline-none">
                                @error('last_name') <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="profile_email" class="block text-[11px] font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1.5">E-posta</label>
                            <input type="email" id="profile_email" value="{{ $user->email }}" disabled
                                   autocomplete="email"
                                   class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30 text-gray-400 dark:text-gray-500 cursor-not-allowed">
                            <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1">E-posta yönetici tarafından değiştirilebilir.</p>
                        </div>

                        <div>
                            <label for="phone" class="block text-[11px] font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1.5">Telefon</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="05xx xxx xx xx"
                                   autocomplete="tel"
                                   class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-gray-800 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-primary-500/30 focus:border-primary-500 transition-all outline-none">
                            @error('phone') <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="profile_department" class="block text-[11px] font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1.5">Departman</label>
                                <input type="text" id="profile_department" value="{{ $user->department?->name ?? '-' }}" disabled
                                       class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30 text-gray-400 dark:text-gray-500 cursor-not-allowed">
                            </div>
                            <div>
                                <label for="profile_hire_date" class="block text-[11px] font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1.5">İşe Giriş</label>
                                <input type="text" id="profile_hire_date" value="{{ $user->hire_date ? $user->hire_date->format('d.m.Y') : '-' }}" disabled
                                       class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30 text-gray-400 dark:text-gray-500 cursor-not-allowed">
                            </div>
                        </div>

                        <button type="submit"
                                class="w-full px-4 py-2.5 text-sm font-bold rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 text-white shadow-md shadow-primary-500/25 hover:shadow-lg hover:shadow-primary-500/35 hover:from-primary-700 hover:to-primary-600 transition-all">
                            Bilgileri Güncelle
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Password Change --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                <div class="w-8 h-8 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white">Şifre Değiştir</h3>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500">Hesabınızı güvende tutmak için şifrenizi güncelleyin</p>
                </div>
            </div>

            <div class="p-5">
                @if(session('success_password'))
                <div class="mb-4 flex items-center gap-2 px-3 py-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 text-xs text-emerald-700 dark:text-emerald-400 font-medium">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success_password') }}
                </div>
                @endif

                <form action="{{ route('staff.profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div>
                            <label for="current_password" class="block text-[11px] font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1.5">Mevcut Şifre</label>
                            <input type="password" id="current_password" name="current_password"
                                   autocomplete="current-password"
                                   class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-gray-800 dark:text-white focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 transition-all outline-none">
                            @error('current_password') <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="new_password" class="block text-[11px] font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1.5">Yeni Şifre</label>
                            <input type="password" id="new_password" name="password"
                                   autocomplete="new-password"
                                   class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-gray-800 dark:text-white focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 transition-all outline-none">
                            @error('password') <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-[11px] font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-1.5">Yeni Şifre (Tekrar)</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   autocomplete="new-password"
                                   class="w-full px-3 py-2.5 text-sm rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700/50 text-gray-800 dark:text-white focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 transition-all outline-none">
                        </div>

                        <button type="submit"
                                class="w-full px-4 py-2.5 text-sm font-bold rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 text-white shadow-md shadow-amber-500/25 hover:shadow-lg hover:shadow-amber-500/35 hover:from-amber-600 hover:to-amber-700 transition-all">
                            Şifreyi Değiştir
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    {{-- Account Info --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-sm font-bold text-gray-800 dark:text-white">Hesap Bilgileri</h3>
        </div>
        <div class="p-5">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl px-4 py-3">
                    <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">Sicil No</p>
                    <p class="text-sm font-mono font-semibold text-gray-700 dark:text-gray-300">{{ $user->registration_number ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl px-4 py-3">
                    <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">Ünvan</p>
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $user->title ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl px-4 py-3">
                    <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wide mb-1">Son Giriş</p>
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                        {{ $user->last_login_at ? $user->last_login_at->format('d.m.Y H:i') : '-' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
