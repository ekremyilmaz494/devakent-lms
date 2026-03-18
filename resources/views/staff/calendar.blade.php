@extends('layouts.staff')
@section('title', 'Takvim')
@section('page-title', 'Takvim')

@section('content')
<div x-data="calendarApp()" x-init="init()" class="space-y-5">

    {{-- Header Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Eğitim Takvimi</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5" x-text="'Toplam ' + events.length + ' etkinlik'"></p>
        </div>
        <div class="flex items-center gap-2">
            <button @click="goToday()"
                    class="px-4 py-2 text-sm font-medium rounded-xl border border-gray-200 dark:border-gray-700
                           bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300
                           hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200 shadow-sm">
                Bugün
            </button>
            <div class="flex items-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
                <button @click="prevMonth()"
                        class="p-2.5 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors border-r border-gray-200 dark:border-gray-700">
                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <span class="px-4 text-sm font-semibold text-gray-800 dark:text-white min-w-[140px] text-center"
                      x-text="monthNames[currentMonth] + ' ' + currentYear"></span>
                <button @click="nextMonth()"
                        class="p-2.5 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors border-l border-gray-200 dark:border-gray-700">
                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Main Grid: Calendar + Sidebar --}}
    <div class="grid grid-cols-1 xl:grid-cols-[1fr_320px] gap-5">

        {{-- Calendar Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">

            {{-- Top gradient stripe --}}
            <div class="h-1 bg-gradient-to-r from-primary-500 via-primary-400 to-blue-400"></div>

            <div class="p-5">
                {{-- Day Headers --}}
                <div class="grid grid-cols-7 mb-2">
                    <template x-for="day in dayNames" :key="day">
                        <div class="text-center py-2">
                            <span class="text-xs font-bold uppercase tracking-wider"
                                  :class="day === 'Cmt' || day === 'Paz'
                                      ? 'text-primary-400 dark:text-primary-500'
                                      : 'text-gray-400 dark:text-gray-500'"
                                  x-text="day"></span>
                        </div>
                    </template>
                </div>

                {{-- Calendar Grid --}}
                <div class="grid grid-cols-7 gap-1">
                    <template x-for="(day, index) in calendarDays" :key="index">
                        <button
                            @click="day.date && selectDate(day.dateStr)"
                            class="relative flex flex-col items-center justify-start p-1.5 rounded-xl min-h-[64px] transition-all duration-150 group"
                            :class="{
                                'cursor-default': !day.date,
                                'cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/40': day.date && !day.isToday && day.dateStr !== selectedDate,
                                'opacity-30': !day.currentMonth,
                                'bg-primary-500 hover:bg-primary-600 shadow-lg shadow-primary-500/30': day.isToday && day.dateStr !== selectedDate,
                                'bg-primary-600 shadow-lg shadow-primary-600/30 ring-2 ring-primary-400 ring-offset-2 dark:ring-offset-gray-800': day.isToday && day.dateStr === selectedDate,
                                'bg-gray-100 dark:bg-gray-700 ring-2 ring-primary-400 dark:ring-primary-500': !day.isToday && day.dateStr === selectedDate,
                            }"
                        >
                            <span class="text-sm font-semibold leading-none mb-1.5"
                                  :class="{
                                      'text-white': day.isToday,
                                      'text-gray-800 dark:text-gray-200': !day.isToday && day.currentMonth,
                                  }"
                                  x-text="day.date"></span>

                            {{-- Event dots --}}
                            <div class="flex flex-wrap justify-center gap-0.5 w-full px-0.5" x-show="day.events && day.events.length > 0">
                                <template x-for="(evt, ei) in (day.events || []).slice(0, 4)" :key="ei">
                                    <span class="w-1.5 h-1.5 rounded-full flex-shrink-0"
                                          :class="day.isToday ? 'bg-white/70' : ''"
                                          :style="day.isToday ? '' : 'background-color:' + evt.color"></span>
                                </template>
                                <span x-show="(day.events || []).length > 4"
                                      class="text-[9px] font-bold leading-none"
                                      :class="day.isToday ? 'text-white/70' : 'text-gray-400 dark:text-gray-500'"
                                      x-text="'+' + ((day.events || []).length - 4)"></span>
                            </div>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Legend --}}
            <div class="px-5 pb-4 pt-1 border-t border-gray-100 dark:border-gray-700/50">
                <div class="flex flex-wrap items-center gap-x-5 gap-y-1.5">
                    <span class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 flex-shrink-0"></span>
                        Başlangıç Tarihi
                    </span>
                    <span class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                        <span class="w-2 h-2 rounded-full bg-red-500 flex-shrink-0"></span>
                        Son Tarih
                    </span>
                    <span class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                        <span class="w-5 h-5 rounded-lg bg-primary-500 text-white text-[10px] flex items-center justify-center font-bold flex-shrink-0">{{ now()->day }}</span>
                        Bugün
                    </span>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="flex flex-col gap-4">

            {{-- Selected Date Events --}}
            <div x-show="selectedDate"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-cloak
                 class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="h-1 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
                <div class="p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-blue-500 dark:text-blue-400 mb-0.5">Seçili Tarih</p>
                            <h3 class="text-sm font-bold text-gray-800 dark:text-white" x-text="formatSelectedDate()"></h3>
                        </div>
                        <button @click="selectedDate = null"
                                class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <template x-if="getSelectedEvents().length === 0">
                        <div class="py-6 text-center">
                            <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500">Bu tarihte etkinlik yok</p>
                        </div>
                    </template>

                    <div class="space-y-2">
                        <template x-for="(evt, i) in getSelectedEvents()" :key="i">
                            <a :href="'/staff/courses/' + evt.course_id"
                               class="flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all group border border-transparent hover:border-gray-200 dark:hover:border-gray-700">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5"
                                     :style="'background-color:' + evt.color + '20'">
                                    <div class="w-2 h-2 rounded-full" :style="'background-color:' + evt.color"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-800 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 truncate" x-text="evt.title"></p>
                                    <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                                        <span class="inline-flex items-center text-[10px] font-semibold px-1.5 py-0.5 rounded-md"
                                              :class="evt.type === 'start'
                                                  ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300'
                                                  : (evt.is_past ? 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400' : 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400')"
                                              x-text="evt.type === 'start' ? '▶ Başlangıç' : (evt.is_past ? '⚠ Geçti' : '⏹ Bitiş')"></span>
                                        <span class="text-[10px] text-gray-400 dark:text-gray-500 truncate" x-text="evt.category"></span>
                                        <template x-if="evt.status === 'completed'">
                                            <span class="inline-flex items-center text-[10px] font-semibold px-1.5 py-0.5 rounded-md bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300">✓ Tamamlandı</span>
                                        </template>
                                    </div>
                                </div>
                            </a>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Upcoming Deadlines --}}
            @if($upcoming->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="h-1 bg-gradient-to-r from-amber-400 to-orange-500"></div>
                <div class="p-4">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-7 h-7 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-amber-600 dark:text-amber-400">Yaklaşan</p>
                            <p class="text-sm font-bold text-gray-800 dark:text-white leading-tight">Son Tarihler</p>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        @foreach($upcoming as $enrollment)
                        <a href="{{ route('staff.courses.show', $enrollment->course_id) }}"
                           class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all group border border-transparent hover:border-gray-200 dark:hover:border-gray-700">
                            <div class="w-1.5 h-10 rounded-full flex-shrink-0" style="background-color: {{ $enrollment->course->category?->color ?? '#14B8A6' }}"></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-gray-800 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 truncate">
                                    {{ $enrollment->course->title }}
                                </p>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">
                                    {{ $enrollment->course->category?->name ?? 'Genel' }}
                                </p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                @php $days = $enrollment->course->end_date->diffInDays(now()); @endphp
                                <p class="text-xs font-bold {{ $days <= 3 ? 'text-red-600 dark:text-red-400' : ($days <= 7 ? 'text-amber-600 dark:text-amber-400' : 'text-gray-500 dark:text-gray-400') }}">
                                    {{ $days }}g
                                </p>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">
                                    {{ $enrollment->course->end_date->format('d.m') }}
                                </p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Stats Card --}}
            <div class="bg-gradient-to-br from-primary-500 to-primary-700 dark:from-primary-600 dark:to-primary-900 rounded-2xl p-4 shadow-lg shadow-primary-500/20">
                <p class="text-xs font-semibold text-primary-200 uppercase tracking-wider mb-3">Bu Ay</p>
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-white/10 rounded-xl p-3">
                        <p class="text-2xl font-bold text-white" x-text="getMonthEventCount('start')"></p>
                        <p class="text-xs text-primary-200 mt-0.5">Başlangıç</p>
                    </div>
                    <div class="bg-white/10 rounded-xl p-3">
                        <p class="text-2xl font-bold text-white" x-text="getMonthEventCount('end')"></p>
                        <p class="text-xs text-primary-200 mt-0.5">Bitiş</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function calendarApp() {
    return {
        events: @json($events),
        currentMonth: new Date().getMonth(),
        currentYear: new Date().getFullYear(),
        selectedDate: null,
        calendarDays: [],
        monthNames: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'],
        dayNames: ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz'],

        init() {
            this.buildCalendar();
        },

        buildCalendar() {
            const days = [];
            const firstDay = new Date(this.currentYear, this.currentMonth, 1);
            let startDay = firstDay.getDay() - 1;
            if (startDay < 0) startDay = 6;

            const daysInMonth = new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
            const daysInPrevMonth = new Date(this.currentYear, this.currentMonth, 0).getDate();

            const today = new Date();
            const todayStr = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0');

            for (let i = startDay - 1; i >= 0; i--) {
                const d = daysInPrevMonth - i;
                const m = this.currentMonth === 0 ? 12 : this.currentMonth;
                const y = this.currentMonth === 0 ? this.currentYear - 1 : this.currentYear;
                const dateStr = y + '-' + String(m).padStart(2, '0') + '-' + String(d).padStart(2, '0');
                days.push({ date: d, dateStr, currentMonth: false, isToday: false, events: this.getEventsForDate(dateStr) });
            }

            for (let d = 1; d <= daysInMonth; d++) {
                const dateStr = this.currentYear + '-' + String(this.currentMonth + 1).padStart(2, '0') + '-' + String(d).padStart(2, '0');
                days.push({ date: d, dateStr, currentMonth: true, isToday: dateStr === todayStr, events: this.getEventsForDate(dateStr) });
            }

            const remaining = 42 - days.length;
            for (let d = 1; d <= remaining; d++) {
                const m = this.currentMonth === 11 ? 1 : this.currentMonth + 2;
                const y = this.currentMonth === 11 ? this.currentYear + 1 : this.currentYear;
                const dateStr = y + '-' + String(m).padStart(2, '0') + '-' + String(d).padStart(2, '0');
                days.push({ date: d, dateStr, currentMonth: false, isToday: false, events: this.getEventsForDate(dateStr) });
            }

            this.calendarDays = days;
        },

        getEventsForDate(dateStr) {
            return this.events.filter(e => e.date === dateStr);
        },

        selectDate(dateStr) {
            this.selectedDate = this.selectedDate === dateStr ? null : dateStr;
        },

        getSelectedEvents() {
            if (!this.selectedDate) return [];
            return this.getEventsForDate(this.selectedDate);
        },

        formatSelectedDate() {
            if (!this.selectedDate) return '';
            const parts = this.selectedDate.split('-');
            const days = ['Pazar', 'Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi'];
            const d = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
            return days[d.getDay()] + ', ' + parseInt(parts[2]) + ' ' + this.monthNames[parseInt(parts[1]) - 1];
        },

        getMonthEventCount(type) {
            // type: 'start' veya 'deadline' (controller'da 'end' yerine 'deadline' kullanılıyor)
            const mapped = type === 'end' ? 'deadline' : type;
            const monthStr = this.currentYear + '-' + String(this.currentMonth + 1).padStart(2, '0');
            return this.events.filter(e => e.date && e.date.startsWith(monthStr) && e.type === mapped).length;
        },

        prevMonth() {
            if (this.currentMonth === 0) { this.currentMonth = 11; this.currentYear--; }
            else { this.currentMonth--; }
            this.buildCalendar();
        },

        nextMonth() {
            if (this.currentMonth === 11) { this.currentMonth = 0; this.currentYear++; }
            else { this.currentMonth++; }
            this.buildCalendar();
        },

        goToday() {
            const today = new Date();
            this.currentMonth = today.getMonth();
            this.currentYear = today.getFullYear();
            this.buildCalendar();
        }
    };
}
</script>
@endpush
@endsection
