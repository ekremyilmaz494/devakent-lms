<div>
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300 rounded-lg text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300 rounded-lg text-sm">{{ session('error') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="relative w-72">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Kategori ara..."
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500">
            <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
        </div>
        <button wire:click="openCreate" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
            + Yeni Kategori
        </button>
    </div>

    {{-- Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($categories as $cat)
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="h-2" style="background-color: {{ $cat->color }}"></div>
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-4 h-4 rounded-full" style="background-color: {{ $cat->color }}"></div>
                        <h3 class="font-semibold text-gray-800 dark:text-white">{{ $cat->name }}</h3>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $cat->courses_count }} egitim</span>
                </div>
                <div class="flex justify-end space-x-2 mt-4">
                    <button wire:click="openEdit({{ $cat->id }})" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 text-sm font-medium">Duzenle</button>
                    <button wire:click="confirmDelete({{ $cat->id }})" class="text-red-600 dark:text-red-400 hover:text-red-800 text-sm font-medium">Sil</button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 py-12 text-center text-gray-400 dark:text-gray-500">Kategori bulunamadi.</div>
        @endforelse
    </div>

    @if($categories->hasPages())
    <div class="mt-4">{{ $categories->links() }}</div>
    @endif

    {{-- Create/Edit Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:click.self="$set('showModal', false)">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md mx-4 p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                {{ $editingId ? 'Kategori Duzenle' : 'Yeni Kategori' }}
            </h3>

            <form wire:submit="save" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori Adi</label>
                    <input wire:model="name" type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Ornegin: Hijyen">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Renk</label>
                    <div class="flex items-center space-x-3">
                        <input wire:model.live="color" type="color" class="w-10 h-10 rounded border border-gray-300 dark:border-gray-600 cursor-pointer">
                        <input wire:model.live="color" type="text" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white font-mono" placeholder="#3B82F6">
                    </div>
                    @error('color') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Preview --}}
                <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="w-4 h-4 rounded-full" style="background-color: {{ $color }}"></div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $name ?: 'Onizleme' }}</span>
                </div>

                <div class="flex justify-end space-x-3 pt-2">
                    <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">Iptal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">{{ $editingId ? 'Guncelle' : 'Kaydet' }}</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Delete Modal --}}
    @if($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:click.self="$set('showDeleteModal', false)">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-sm mx-4 p-6 text-center">
            <svg class="w-12 h-12 mx-auto text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Kategoriyi Sil</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Bu kategoriyi silmek istediginizden emin misiniz?</p>
            <div class="flex justify-center space-x-3">
                <button wire:click="$set('showDeleteModal', false)" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">Vazgec</button>
                <button wire:click="delete" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">Evet, Sil</button>
            </div>
        </div>
    </div>
    @endif
</div>
