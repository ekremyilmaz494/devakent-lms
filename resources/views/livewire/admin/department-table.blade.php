<div>
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="relative w-72">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Departman ara..."
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
            <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
        </div>
        <button wire:click="openCreate" class="px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
            + Yeni Departman
        </button>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Departman Adi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Aciklama</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Personel</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Durum</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Islemler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($departments as $dept)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <td class="px-6 py-4 font-medium text-gray-800 dark:text-white">{{ $dept->name }}</td>
                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ Str::limit($dept->description, 50) ?: '-' }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 dark:bg-primary-900/50 text-primary-700 dark:text-primary-300">
                            {{ $dept->users_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $dept->is_active ? 'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300' }}">
                            {{ $dept->is_active ? 'Aktif' : 'Pasif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <button wire:click="openEdit({{ $dept->id }})" class="text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 text-sm font-medium">Duzenle</button>
                        <button wire:click="confirmDelete({{ $dept->id }})" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm font-medium">Sil</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">Departman bulunamadi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($departments->hasPages())
        <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700">
            {{ $departments->links() }}
        </div>
        @endif
    </div>

    {{-- Create/Edit Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 modal-backdrop-enter" wire:click.self="$set('showModal', false)">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md mx-4 p-6 modal-content-enter">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                {{ $editingId ? 'Departman Duzenle' : 'Yeni Departman' }}
            </h3>

            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Departman Adi</label>
                    <input wire:model="name" type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500" placeholder="Ornegin: Dahiliye">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Aciklama</label>
                    <textarea wire:model="description" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500" placeholder="Departman aciklamasi (istege bagli)"></textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center">
                    <input wire:model="is_active" type="checkbox" id="is_active" class="rounded border-gray-300 dark:border-gray-600 text-primary-600 focus:ring-primary-500">
                    <label for="is_active" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Aktif</label>
                </div>

                <div class="flex justify-end space-x-3 pt-2">
                    <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        Iptal
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors disabled:opacity-50" wire:loading.attr="disabled" wire:target="save">
                        <span wire:loading.remove wire:target="save">{{ $editingId ? 'Guncelle' : 'Kaydet' }}</span>
                        <span wire:loading wire:target="save" class="inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            Kaydediliyor...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 modal-backdrop-enter" wire:click.self="$set('showDeleteModal', false)">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-sm mx-4 p-6 text-center modal-content-enter">
            <svg class="w-12 h-12 mx-auto text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Departmani Sil</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Bu departmani silmek istediginizden emin misiniz? Bu islem geri alinamaz.</p>
            <div class="flex justify-center space-x-3">
                <button wire:click="$set('showDeleteModal', false)" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    Vazgec
                </button>
                <button wire:click="delete" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors disabled:opacity-50" wire:loading.attr="disabled" wire:target="delete">
                    <span wire:loading.remove wire:target="delete">Evet, Sil</span>
                    <span wire:loading wire:target="delete" class="inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        Siliniyor...
                    </span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
