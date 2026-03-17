<div>
    {{-- Page Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Raporlar</h2>
        <div class="flex items-center gap-2">
            <button wire:click="exportExcel" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-3.5 h-3.5 text-emerald-600" wire:loading.class="animate-spin" wire:target="exportExcel" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <span wire:loading.remove wire:target="exportExcel">Excel İndir</span>
                <span wire:loading wire:target="exportExcel">Hazırlanıyor...</span>
            </button>
            <button wire:click="exportPdf" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-3.5 h-3.5 text-red-500" wire:loading.class="animate-spin" wire:target="exportPdf" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                <span wire:loading.remove wire:target="exportPdf">PDF İndir</span>
                <span wire:loading wire:target="exportPdf">Hazırlanıyor...</span>
            </button>
        </div>
    </div>

    {{-- Tab Navigation --}}
    <div class="flex gap-1 p-1 bg-gray-100 dark:bg-gray-800 rounded-xl mb-6 overflow-x-auto">
        @foreach([
            'course' => ['Eğitim Bazlı', 'M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342'],
            'department' => ['Departman Bazlı', 'M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 0h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z'],
            'staff' => ['Personel Bazlı', 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z'],
            'time' => ['Zaman Bazlı', 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5'],
        ] as $tab => $info)
            <button wire:click="$set('activeTab', '{{ $tab }}')"
                class="flex items-center gap-2 px-4 py-2.5 text-xs font-semibold rounded-lg whitespace-nowrap transition-all {{ $activeTab === $tab ? 'bg-white dark:bg-gray-700 text-primary-700 dark:text-primary-300 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $info[1] }}" /></svg>
                {{ $info[0] }}
            </button>
        @endforeach
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl p-4 text-white shadow-lg shadow-primary-500/20">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347" /></svg>
                </div>
            </div>
            <p class="text-2xl font-bold">{{ $stats['activeCourses'] }}</p>
            <p class="text-xs text-white/70 mt-1">Aktif Eğitim</p>
        </div>
        <div class="bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl p-4 text-white shadow-lg shadow-primary-400/20">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0" /></svg>
                </div>
            </div>
            <p class="text-2xl font-bold">{{ $stats['totalEnrollments'] }}</p>
            <p class="text-xs text-white/70 mt-1">Toplam Kayıt</p>
        </div>
        <div class="bg-gradient-to-br from-primary-600 to-primary-800 rounded-xl p-4 text-white shadow-lg shadow-primary-600/20">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            @php $compRate = $stats['totalEnrollments'] > 0 ? round($stats['completedEnrollments'] / $stats['totalEnrollments'] * 100, 1) : 0; @endphp
            <p class="text-2xl font-bold">%{{ $compRate }}</p>
            <p class="text-xs text-white/70 mt-1">Tamamlanma Oranı</p>
        </div>
        <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl p-4 text-white shadow-lg shadow-amber-500/20">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
                </div>
            </div>
            <p class="text-2xl font-bold">{{ $stats['avgScore'] }}</p>
            <p class="text-xs text-white/70 mt-1">Ort. Sınav Puanı</p>
        </div>
    </div>

    {{-- Report Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
        <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
        {{-- Course Report --}}
        @if($activeTab === 'course')
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Eğitim Detay Tablosu</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50/80 dark:bg-gray-700/40 border-b border-gray-200 dark:border-gray-700">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Eğitim</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Kategori</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Kayıt</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Tamamlanma %</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Ort. Ön Sınav</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Ort. Son Sınav</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($reportData as $row)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $row['title'] }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-xs font-medium" style="background-color: {{ $row['category_color'] }}15; color: {{ $row['category_color'] }};">
                                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $row['category_color'] }};"></span>
                                        {{ $row['category'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-300">{{ $row['enrollments'] }}</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-16 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full {{ $row['completion_rate'] >= 80 ? 'bg-emerald-500' : ($row['completion_rate'] >= 50 ? 'bg-amber-500' : 'bg-primary-500') }}" style="width: {{ $row['completion_rate'] }}%"></div>
                                        </div>
                                        <span class="text-xs font-semibold {{ $row['completion_rate'] >= 80 ? 'text-emerald-600' : ($row['completion_rate'] >= 50 ? 'text-amber-600' : 'text-primary-600') }}">%{{ $row['completion_rate'] }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">{{ $row['pre_exam_avg'] }}</td>
                                <td class="px-4 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">{{ $row['post_exam_avg'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-sm text-gray-500">Henüz veri bulunmuyor</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Department Report --}}
        @if($activeTab === 'department')
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Departman Detay Tablosu</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50/80 dark:bg-gray-700/40 border-b border-gray-200 dark:border-gray-700">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Departman</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Personel</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Kayıt</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Tamamlanan</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Tamamlanma %</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Ort. Ön Sınav</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Ort. Son Sınav</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($reportData as $row)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $row['name'] }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-semibold bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-400">{{ $row['staff_count'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-300">{{ $row['enrollments'] }}</td>
                                <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-300">{{ $row['completed'] }}</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-16 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full {{ $row['completion_rate'] >= 80 ? 'bg-emerald-500' : ($row['completion_rate'] >= 50 ? 'bg-amber-500' : 'bg-primary-500') }}" style="width: {{ $row['completion_rate'] }}%"></div>
                                        </div>
                                        <span class="text-xs font-semibold {{ $row['completion_rate'] >= 80 ? 'text-emerald-600' : ($row['completion_rate'] >= 50 ? 'text-amber-600' : 'text-primary-600') }}">%{{ $row['completion_rate'] }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">{{ $row['pre_exam_avg'] }}</td>
                                <td class="px-4 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">{{ $row['post_exam_avg'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-sm text-gray-500">Henüz veri bulunmuyor</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Staff Report --}}
        @if($activeTab === 'staff')
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Personel Detay Tablosu</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50/80 dark:bg-gray-700/40 border-b border-gray-200 dark:border-gray-700">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Personel</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Departman</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Kayıt</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Tamamlanma %</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Ort. Ön Sınav</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Ort. Son Sınav</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Sertifika</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Son Giriş</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($reportData as $row)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $row['name'] }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-slate-100 dark:bg-slate-700/60 text-slate-700 dark:text-slate-300">{{ $row['department'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-300">{{ $row['enrollments'] }}</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-16 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full {{ $row['completion_rate'] >= 80 ? 'bg-emerald-500' : ($row['completion_rate'] >= 50 ? 'bg-amber-500' : 'bg-primary-500') }}" style="width: {{ $row['completion_rate'] }}%"></div>
                                        </div>
                                        <span class="text-xs font-semibold {{ $row['completion_rate'] >= 80 ? 'text-emerald-600' : ($row['completion_rate'] >= 50 ? 'text-amber-600' : 'text-primary-600') }}">%{{ $row['completion_rate'] }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">{{ $row['pre_exam_avg'] }}</td>
                                <td class="px-4 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">{{ $row['post_exam_avg'] }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400">{{ $row['certificates'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400">{{ $row['last_login'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-12 text-center text-sm text-gray-500">Henüz veri bulunmuyor</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Time Report --}}
        @if($activeTab === 'time')
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Son 6 Ay — Aylık Özet</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50/80 dark:bg-gray-700/40 border-b border-gray-200 dark:border-gray-700">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Ay</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Toplam Kayıt</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Tamamlanan</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Tamamlanma %</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($reportData as $row)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $row['month'] }}</td>
                                <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-300">{{ $row['total'] }}</td>
                                <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-300">{{ $row['completed'] }}</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-20 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full {{ $row['completion_rate'] >= 80 ? 'bg-emerald-500' : ($row['completion_rate'] >= 50 ? 'bg-amber-500' : 'bg-primary-500') }}" style="width: {{ $row['completion_rate'] }}%"></div>
                                        </div>
                                        <span class="text-xs font-semibold {{ $row['completion_rate'] >= 80 ? 'text-emerald-600' : ($row['completion_rate'] >= 50 ? 'text-amber-600' : 'text-primary-600') }}">%{{ $row['completion_rate'] }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-12 text-center text-sm text-gray-500">Son 6 ayda veri bulunmuyor</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
