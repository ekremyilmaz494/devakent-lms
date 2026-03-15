<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-3 flex-1">
            {{-- Search --}}
            <div class="relative flex-1 max-w-sm">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Ad, soyad, e-posta veya sicil no..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500" />
            </div>
            {{-- Department Filter --}}
            <select wire:model.live="filterDepartment" class="border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2">
                <option value="">Tüm Departmanlar</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                @endforeach
            </select>
            {{-- Status Filter --}}
            <select wire:model.live="filterStatus" class="border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2">
                <option value="">Tüm Durumlar</option>
                <option value="1">Aktif</option>
                <option value="0">Pasif</option>
            </select>
        </div>
        <button wire:click="openCreate" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Yeni Personel
        </button>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Personel</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">E-posta</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Departman</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-500 dark:text-gray-400">Ünvan</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-500 dark:text-gray-400">Eğitim</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-500 dark:text-gray-400">Durum</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-500 dark:text-gray-400">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($staff as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center flex-shrink-0">
                                        <span class="text-sm font-medium text-blue-700 dark:text-blue-300">{{ strtoupper(substr($user->first_name ?? '', 0, 1) . substr($user->last_name ?? '', 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $user->full_name }}</p>
                                        @if($user->registration_number)
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->registration_number }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                @if($user->department)
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                        {{ $user->department->name }}
                                    </span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $user->title ?? '—' }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">
                                    {{ $user->enrollments_count }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button wire:click="toggleActive({{ $user->id }})" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium transition-colors {{ $user->is_active ? 'bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 hover:bg-green-100' : 'bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 hover:bg-red-100' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Pasif' }}
                                </button>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <button wire:click="viewDetail({{ $user->id }})" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors" title="Detay">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </button>
                                    <button wire:click="openEdit({{ $user->id }})" class="p-1.5 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 rounded-lg transition-colors" title="Düzenle">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $user->id }})" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="Sil">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                <p>Personel bulunamadı.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($staff->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $staff->links() }}
            </div>
        @endif
    </div>

    {{-- Create/Edit Modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="$set('showModal', false)"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-lg p-6 z-10">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        {{ $editingId ? 'Personel Düzenle' : 'Yeni Personel' }}
                    </h3>

                    <form wire:submit="save" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            {{-- First Name --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ad *</label>
                                <input wire:model="first_name" type="text" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500" />
                                @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            {{-- Last Name --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Soyad *</label>
                                <input wire:model="last_name" type="text" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500" />
                                @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">E-posta *</label>
                            <input wire:model="email" type="email" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500" />
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Password --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Şifre {{ $editingId ? '(boş bırakılırsa değişmez)' : '*' }}
                            </label>
                            <input wire:model="password" type="password" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500" />
                            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            {{-- Phone --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telefon</label>
                                <input wire:model="phone" type="text" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                            {{-- Registration Number --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sicil No</label>
                                <input wire:model="registration_number" type="text" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500" />
                                @error('registration_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            {{-- Title --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ünvan</label>
                                <input wire:model="title" type="text" placeholder="örn: Hemşire, Dr., Teknisyen" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                            {{-- Department --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Departman *</label>
                                <select wire:model="department_id" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Seçiniz</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                                @error('department_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            {{-- Hire Date --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">İşe Giriş Tarihi</label>
                                <input wire:model="hire_date" type="date" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                            {{-- Active Status --}}
                            <div class="flex items-end pb-1">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input wire:model="is_active" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Aktif</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                İptal
                            </button>
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                                {{ $editingId ? 'Güncelle' : 'Oluştur' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Detail Modal --}}
    @if($showDetailModal && $viewingUser)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="$set('showDetailModal', false)"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-lg p-6 z-10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Personel Detayı</h3>
                        <button wire:click="$set('showDetailModal', false)" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center">
                            <span class="text-xl font-bold text-blue-700 dark:text-blue-300">{{ strtoupper(substr($viewingUser->first_name ?? '', 0, 1) . substr($viewingUser->last_name ?? '', 0, 1)) }}</span>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $viewingUser->full_name }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $viewingUser->title ?? 'Ünvan belirtilmemiş' }}</p>
                        </div>
                    </div>

                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">E-posta</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $viewingUser->email }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Telefon</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $viewingUser->phone ?? '—' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Sicil No</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $viewingUser->registration_number ?? '—' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Departman</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $viewingUser->department?->name ?? '—' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">İşe Giriş</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $viewingUser->hire_date?->format('d.m.Y') ?? '—' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Son Giriş</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $viewingUser->last_login_at?->format('d.m.Y H:i') ?? 'Henüz giriş yapmadı' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Durum</dt>
                            <dd>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $viewingUser->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $viewingUser->is_active ? 'Aktif' : 'Pasif' }}
                                </span>
                            </dd>
                        </div>
                    </dl>

                    {{-- Stats --}}
                    <div class="grid grid-cols-2 gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 text-center">
                            <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $viewingUser->enrollments_count }}</p>
                            <p class="text-xs text-blue-600 dark:text-blue-400">Eğitim Kaydı</p>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-3 text-center">
                            <p class="text-2xl font-bold text-green-700 dark:text-green-300">{{ $viewingUser->certificates_count }}</p>
                            <p class="text-xs text-green-600 dark:text-green-400">Sertifika</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="$set('showDeleteModal', false)"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-sm p-6 z-10">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Personeli Sil</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Bu personeli silmek istediğinizden emin misiniz? Bu işlem geri alınabilir (soft delete).</p>
                        <div class="flex gap-3">
                            <button wire:click="$set('showDeleteModal', false)" class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                İptal
                            </button>
                            <button wire:click="delete" class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                                Sil
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
