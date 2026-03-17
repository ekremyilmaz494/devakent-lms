@extends('layouts.staff')
@section('title', 'Profilim')
@section('page-title', 'Profilim')

@section('content')
<div class="space-y-6">

    {{-- Profile Header --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
        <div class="p-6">
            <div class="flex flex-col sm:flex-row items-center gap-5">
                {{-- Avatar --}}
                <div class="w-20 h-20 bg-primary-100 dark:bg-primary-900/30 rounded-2xl flex items-center justify-center flex-shrink-0">
                    @if($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->full_name }}" class="w-20 h-20 rounded-2xl object-cover">
                    @else
                        <span class="text-3xl font-bold text-primary-600 dark:text-primary-400">{{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}</span>
                    @endif
                </div>

                {{-- User Info --}}
                <div class="text-center sm:text-left">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ $user->full_name }}</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->title ?? 'Personel' }} &middot; {{ $user->department?->name ?? 'Departman atanmamış' }}</p>
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 mt-2 text-xs text-gray-500 dark:text-gray-400">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            {{ $user->email }}
                        </span>
                        @if($user->phone)
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            {{ $user->phone }}
                        </span>
                        @endif
                        @if($user->registration_number)
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                            {{ $user->registration_number }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Personal Information Form --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-blue-400 to-blue-600"></div>
            <div class="p-5">
                <h3 class="text-sm font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Kişisel Bilgiler
                </h3>

                <form action="{{ route('staff.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Ad</label>
                                <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors">
                                @error('first_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Soyad</label>
                                <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors">
                                @error('last_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">E-posta</label>
                            <input type="email" value="{{ $user->email }}" disabled
                                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">E-posta adresi yönetici tarafından değiştirilebilir.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Telefon</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="05xx xxx xx xx"
                                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors">
                            @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Read-only fields --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Departman</label>
                                <input type="text" value="{{ $user->department?->name ?? '-' }}" disabled
                                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">İşe Giriş Tarihi</label>
                                <input type="text" value="{{ $user->hire_date ? $user->hire_date->format('d.m.Y') : '-' }}" disabled
                                       class="w-full px-3 py-2 text-sm rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                            </div>
                        </div>

                        <button type="submit" class="w-full px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 transition-colors">
                            Bilgileri Güncelle
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Password Change Form --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-amber-400 to-amber-600"></div>
            <div class="p-5">
                <h3 class="text-sm font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Şifre Değiştir
                </h3>

                <form action="{{ route('staff.profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Mevcut Şifre</label>
                            <input type="password" name="current_password"
                                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors">
                            @error('current_password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Yeni Şifre</label>
                            <input type="password" name="password"
                                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors">
                            @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Yeni Şifre (Tekrar)</label>
                            <input type="password" name="password_confirmation"
                                   class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors">
                        </div>

                        <button type="submit" class="w-full px-4 py-2 text-sm font-medium rounded-lg bg-amber-600 text-white hover:bg-amber-700 transition-colors">
                            Şifreyi Değiştir
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Account Info --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="h-1.5 bg-gradient-to-r from-gray-400 to-gray-600"></div>
        <div class="p-5">
            <h3 class="text-sm font-bold text-gray-800 dark:text-white mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Hesap Bilgileri
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-500">Sicil No:</span>
                    <span class="font-mono">{{ $user->registration_number ?? '-' }}</span>
                </div>
                <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-500">Ünvan:</span>
                    <span>{{ $user->title ?? '-' }}</span>
                </div>
                <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-500">Son Giriş:</span>
                    <span>{{ $user->last_login_at ? $user->last_login_at->format('d.m.Y H:i') : '-' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
