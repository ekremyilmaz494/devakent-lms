<div>
    <form wire:submit="save" class="space-y-6">
        {{-- Validation Error Summary --}}
        @if($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/40 flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-red-800 dark:text-red-300 mb-1">Lütfen aşağıdaki alanları kontrol edin</h4>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li class="text-xs text-red-700 dark:text-red-400">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Basic Info Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
            <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Temel Bilgiler</h3>

            <div class="space-y-4">
                {{-- Title --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Eğitim Başlığı *</label>
                    <input wire:model="title" type="text" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" placeholder="örn: Temel Hijyen Eğitimi" />
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Açıklama</label>
                    <textarea wire:model="description" rows="3" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" placeholder="Eğitimin kısa açıklaması..."></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Category --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori *</label>
                        <select wire:model="category_id" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Seçiniz</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Durum</label>
                        <select wire:model="status" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500">
                            <option value="draft">Taslak</option>
                            <option value="published">Yayında</option>
                        </select>
                    </div>
                    {{-- Mandatory --}}
                    <div class="flex items-end pb-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input wire:model="is_mandatory" type="checkbox" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500" />
                            <span class="text-sm text-gray-700 dark:text-gray-300">Zorunlu Eğitim</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Start Date --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Başlangıç Tarihi</label>
                        <input wire:model="start_date" type="date" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                    </div>
                    {{-- End Date --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bitiş Tarihi</label>
                        <input wire:model="end_date" type="date" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                        @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            </div>
        </div>

        {{-- Videos Card (Çoklu) --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
            <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Videolar</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ count($videos) }} video eklendi</p>
                </div>
                <button type="button" wire:click="addVideo" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-primary-600 border border-primary-300 rounded-lg hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Video Ekle
                </button>
            </div>

            @if(count($videos) === 0)
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                    <p>Henüz video eklenmemiş.</p>
                    <p class="text-sm mt-1">Yukarıdaki "Video Ekle" butonuna tıklayarak başlayın.</p>
                </div>
            @endif

            <div class="space-y-4">
                @foreach($videos as $index => $video)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4" wire:key="video-{{ $index }}">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 text-xs font-bold">#{{ $index + 1 }}</span>
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Video {{ $index + 1 }}</h4>
                            </div>
                            <div class="flex items-center gap-1">
                                @if($index > 0)
                                    <button type="button" wire:click="moveVideoUp({{ $index }})" class="p-1 text-gray-400 hover:text-gray-600 rounded" title="Yukarı taşı">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                    </button>
                                @endif
                                @if($index < count($videos) - 1)
                                    <button type="button" wire:click="moveVideoDown({{ $index }})" class="p-1 text-gray-400 hover:text-gray-600 rounded" title="Aşağı taşı">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                @endif
                                <button type="button" wire:click="removeVideo({{ $index }})" class="p-1 text-red-400 hover:text-red-600 rounded" title="Sil">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-3">
                            {{-- Video Başlığı --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Video Başlığı *</label>
                                <input wire:model="videos.{{ $index }}.title" type="text" placeholder="örn: Giriş Videosu" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                                @error("videos.{$index}.title") <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Mevcut Video Göstergesi --}}
                            @if(!empty($video['existing_path']))
                                <div class="flex items-center gap-2 p-2 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                    <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    <span class="text-xs text-green-700 dark:text-green-300 truncate">Mevcut: {{ basename($video['existing_path']) }}</span>
                                </div>
                            @endif

                            {{-- Dosya Yükleme --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                    {{ !empty($video['existing_path']) ? 'Yeni Video Yükle (isteğe bağlı)' : 'Video Dosyası *' }}
                                </label>
                                <input wire:model="videoFiles.{{ $index }}" type="file" accept="video/mp4,video/avi,video/quicktime,video/x-ms-wmv"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 cursor-pointer file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-primary-100 file:text-primary-700 file:cursor-pointer dark:file:bg-primary-900/40 dark:file:text-primary-300" />
                                <div wire:loading wire:target="videoFiles.{{ $index }}" class="flex items-center gap-2 mt-1.5 text-xs text-primary-600 dark:text-primary-400">
                                    <svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    Video yükleniyor...
                                </div>
                                @error("videoFiles.{$index}") <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if(count($videos) > 0)
                <p class="text-xs text-gray-500 mt-3">Desteklenen formatlar: MP4, AVI, MOV, WMV (Maks. 500MB). Videolar yukarıdaki sıraya göre izlenecektir.</p>
            @endif
            </div>
        </div>

        {{-- Exam Settings Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
            <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Sınav Ayarları</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sınav Süresi (dk)</label>
                    <input wire:model="exam_duration_minutes" type="number" min="5" max="180" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Geçme Puanı (%)</label>
                    <input wire:model="passing_score" type="number" min="1" max="100" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Maks. Deneme Hakkı</label>
                    <input wire:model="max_attempts" type="number" min="1" max="10" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                </div>
            </div>
            </div>
        </div>

        {{-- Department Assignment Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
            <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Departman Ataması</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Bu eğitimin hangi departmanlara atanacağını seçin.</p>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach($departments as $dept)
                    <label class="flex items-center gap-2 p-2 rounded-lg border cursor-pointer transition-colors {{ in_array($dept->id, $selectedDepartments) ? 'border-primary-500 bg-primary-100 dark:bg-primary-900/30' : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                        <input wire:model="selectedDepartments" type="checkbox" value="{{ $dept->id }}" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500" />
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $dept->name }}</span>
                    </label>
                @endforeach
            </div>
            </div>
        </div>

        {{-- Questions Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
            <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sorular</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ count($questions) }} soru eklendi</p>
                </div>
                <button type="button" wire:click="addQuestion" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-primary-600 border border-primary-300 rounded-lg hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Soru Ekle
                </button>
            </div>

            @if(count($questions) === 0)
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p>Henüz soru eklenmemiş.</p>
                    <p class="text-sm mt-1">Yukarıdaki "Soru Ekle" butonuna tıklayarak başlayın.</p>
                </div>
            @endif

            <div class="space-y-4">
                @foreach($questions as $index => $question)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4" wire:key="question-{{ $index }}">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Soru {{ $index + 1 }}</h4>
                            <div class="flex items-center gap-1">
                                @if($index > 0)
                                    <button type="button" wire:click="moveQuestionUp({{ $index }})" class="p-1 text-gray-400 hover:text-gray-600 rounded" title="Yukarı taşı">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                    </button>
                                @endif
                                @if($index < count($questions) - 1)
                                    <button type="button" wire:click="moveQuestionDown({{ $index }})" class="p-1 text-gray-400 hover:text-gray-600 rounded" title="Aşağı taşı">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                @endif
                                <button type="button" wire:click="removeQuestion({{ $index }})" class="p-1 text-red-400 hover:text-red-600 rounded" title="Sil">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Question Text --}}
                        <div class="mb-3">
                            <input wire:model="questions.{{ $index }}.question_text" type="text" placeholder="Soru metnini yazın..." class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                            @error("questions.{$index}.question_text") <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Options --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach(['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D'] as $key => $label)
                                <div class="flex items-center gap-2">
                                    <label class="flex items-center gap-1.5 flex-shrink-0 cursor-pointer">
                                        <input wire:model="questions.{{ $index }}.correct_option" type="radio" value="{{ $key }}" name="correct_{{ $index }}" class="text-green-600 focus:ring-green-500" />
                                        <span class="text-xs font-bold w-5 h-5 flex items-center justify-center rounded {{ $questions[$index]['correct_option'] === $key ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $label }}</span>
                                    </label>
                                    <input wire:model="questions.{{ $index }}.option_{{ $key }}" type="text" placeholder="{{ $label }} şıkkı" class="flex-1 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1.5 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
            <a href="{{ route('admin.courses.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                Geri Dön
            </a>
            <div class="flex gap-3">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled" wire:target="save">
                    <span wire:loading.remove wire:target="save">
                        <svg class="w-4 h-4 inline -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        {{ $courseId ? 'Güncelle' : 'Eğitimi Oluştur' }}
                    </span>
                    <span wire:loading wire:target="save" class="inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        Kaydediliyor...
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>
