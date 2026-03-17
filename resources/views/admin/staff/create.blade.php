@extends('layouts.admin')
@section('title', __('lms.add_staff'))
@section('page-title', __('lms.new_staff'))

@section('breadcrumb')
    @include('layouts.partials.breadcrumb', ['items' => [
        ['label' => __('lms.staff_title'), 'url' => route('admin.staff.index')],
        ['label' => __('lms.new_staff')],
    ]])
@endsection

@section('content')
<div class="space-y-6">

    {{-- Hero Banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-violet-700 via-violet-600 to-purple-600 shadow-xl shadow-violet-500/20">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-10 -right-10 w-56 h-56 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-14 -left-10 w-72 h-72 bg-purple-400/10 rounded-full blur-3xl"></div>
        </div>
        <div class="relative px-6 py-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-white/15 backdrop-blur-sm rounded-2xl flex items-center justify-center flex-shrink-0 border border-white/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-black text-white tracking-tight">{{ __('lms.new_staff') }}</h1>
                <p class="text-violet-100 text-sm mt-0.5">Sisteme yeni bir çalışan kaydı oluşturun</p>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="absolute top-0 inset-x-0 h-0.5 bg-gradient-to-r from-violet-400 to-purple-600"></div>

        <form method="POST" action="{{ route('admin.staff.store') }}" class="p-6 space-y-6">
            @csrf

            {{-- Validation Errors --}}
            @if($errors->any())
                <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                    <p class="text-sm font-semibold text-red-700 dark:text-red-400 mb-2">{{ __('lms.validation_errors') }}:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="text-sm text-red-600 dark:text-red-400">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Section: Kişisel Bilgiler --}}
            <div>
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">{{ __('lms.basic_info') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="first_name" class="block text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5">{{ __('lms.first_name') }} <span class="text-red-500" aria-hidden="true">*</span></label>
                        <input id="first_name" name="first_name" type="text" autocomplete="given-name" value="{{ old('first_name') }}"
                            class="w-full border @error('first_name') border-red-400 dark:border-red-600 @else border-gray-300 dark:border-gray-700 @enderror rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-violet-500 focus:border-violet-500">
                        @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="last_name" class="block text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5">{{ __('lms.last_name') }} <span class="text-red-500" aria-hidden="true">*</span></label>
                        <input id="last_name" name="last_name" type="text" autocomplete="family-name" value="{{ old('last_name') }}"
                            class="w-full border @error('last_name') border-red-400 dark:border-red-600 @else border-gray-300 dark:border-gray-700 @enderror rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-violet-500 focus:border-violet-500">
                        @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5">{{ __('lms.email') }} <span class="text-red-500" aria-hidden="true">*</span></label>
                        <input id="email" name="email" type="email" autocomplete="email" value="{{ old('email') }}"
                            class="w-full border @error('email') border-red-400 dark:border-red-600 @else border-gray-300 dark:border-gray-700 @enderror rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-violet-500 focus:border-violet-500">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5">{{ __('lms.phone') }}</label>
                        <input id="phone" name="phone" type="tel" autocomplete="tel" value="{{ old('phone') }}"
                            placeholder="0555 000 00 00"
                            class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-violet-500 focus:border-violet-500">
                    </div>
                </div>
            </div>

            {{-- Section: Çalışma Bilgileri --}}
            <div>
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">Çalışma Bilgileri</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="department_id" class="block text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5">{{ __('lms.department') }} <span class="text-red-500" aria-hidden="true">*</span></label>
                        <select id="department_id" name="department_id"
                            class="w-full border @error('department_id') border-red-400 dark:border-red-600 @else border-gray-300 dark:border-gray-700 @enderror rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-violet-500 focus:border-violet-500">
                            <option value="">{{ __('lms.select') }}</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        @error('department_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="title" class="block text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5">{{ __('lms.title') }}</label>
                        <input id="title" name="title" type="text" value="{{ old('title') }}"
                            placeholder="örn: Hemşire, Dr., Teknisyen"
                            class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-violet-500 focus:border-violet-500">
                    </div>
                    <div>
                        <label for="registration_number" class="block text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5">{{ __('lms.registration_number') }}</label>
                        <input id="registration_number" name="registration_number" type="text" value="{{ old('registration_number') }}"
                            class="w-full border @error('registration_number') border-red-400 dark:border-red-600 @else border-gray-300 dark:border-gray-700 @enderror rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-violet-500 focus:border-violet-500">
                        @error('registration_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="hire_date" class="block text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5">{{ __('lms.hire_date_label') }}</label>
                        <input id="hire_date" name="hire_date" type="date" value="{{ old('hire_date') }}"
                            class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-violet-500 focus:border-violet-500">
                    </div>
                </div>
                <div class="mt-5 flex items-center gap-3">
                    <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', '1') ? 'checked' : '' }}
                        class="rounded border-gray-300 text-violet-600 focus:ring-violet-500 w-4 h-4">
                    <label for="is_active" class="text-sm text-gray-700 dark:text-gray-300">Hesabı aktif olarak oluştur</label>
                </div>
            </div>

            {{-- Section: Güvenlik --}}
            <div>
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">{{ __('lms.security_settings') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="password" class="block text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5">Şifre <span class="text-red-500" aria-hidden="true">*</span></label>
                        <input id="password" name="password" type="password" autocomplete="new-password"
                            class="w-full border @error('password') border-red-400 dark:border-red-600 @else border-gray-300 dark:border-gray-700 @enderror rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-violet-500 focus:border-violet-500">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">En az 6 karakter olmalıdır.</p>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.staff.index') }}"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    {{ __('lms.cancel') }}
                </a>
                <button type="submit"
                    class="px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-violet-600 to-purple-600 rounded-lg hover:from-violet-700 hover:to-purple-700 transition-all shadow-sm shadow-violet-500/25">
                    {{ __('lms.add_staff') }}
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
