@extends('layouts.staff')
@section('title', 'Takvim')
@section('page-title', 'Takvim')

@section('content')
<div class="space-y-6" x-data="calendarApp()" x-init="init()">

    {{-- Month Navigation + Calendar --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
        <div class="p-5">
            <div class="flex items-center justify-between mb-5">
                <button @click="prevMonth()" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <div class="text-center">
                    <h2 class="text-lg font-bold text-gray-800 dark:text-white" x-text="monthNames[currentMonth] + ' ' + currentYear"></h2>
                    <button @click="goToday()" class="text-xs font-medium mt-1 px-3 py-0.5 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 hover:bg-primary-200 dark:hover:bg-primary-900/50 transition-colors">
                        Bugün
                    </button>
                </div>
                <button @click="nextMonth()" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

            {{-- Day Headers --}}
            <div class="grid grid-cols-7 gap-1 mb-2">
                <template x-for="day in dayNames" :key="day">
                    <div class="text-center text-xs font-semibold text-gray-500 dark:text-gray-400 py-2" x-text="day"></div>
                </template>
            </div>

            {{-- Calendar Grid --}}
            <div class="grid grid-cols-7 gap-1">
                <template x-for="(day, index) in calendarDays" :key="index">
                    <button
                        @click="day.date && selectDate(day.dateStr)"
                        class="relative aspect-square p-1 rounded-lg text-sm transition-all"
                        :class="{
                            'hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer': day.date,
                            'text-gray-300 dark:text-gray-600': !day.currentMonth,
                            'text-gray-700 dark:text-gray-300': day.currentMonth && !day.isToday,
                            'bg-primary-500 text-white font-bold hover:bg-primary-600': day.isToday,
                            'ring-2 ring-primary-400 dark:ring-primary-500': day.dateStr === selectedDate && !day.isToday,
                            'ring-2 ring-white dark:ring-gray-900': day.dateStr === selectedDate && day.isToday,
                        }"
                    >
                        <span class="block text-center" x-text="day.date"></span>
                        <div class="flex justify-center gap-0.5 mt-0.5" x-show="day.events && day.events.length > 0">
                            <template x-for="(evt, ei) in (day.events || []).slice(0, 3)" :key="ei">
                                <span class="w-1.5 h-1.5 rounded-full" :style="'background-color:' + evt.color"></span>
                            </template>
                        </div>
                    </button>
                </template>
            </div>
        </div>
    </div>

    {{-- Selected Date Events --}}
    <div x-show="selectedDate"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-cloak>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-blue-400 to-blue-600"></div>
            <div class="p-5">
                <h3 class="text-sm font-bold text-gray-800 dark:text-white mb-3">
                    <span x-text="formatSelectedDate()"></span> tarihindeki etkinlikler
                </h3>

                <template x-if="getSelectedEvents().length === 0">
                    <p class="text-sm text-gray-500 dark:text-gray-400 py-4 text-center">Bu tarihte etkinlik bulunmuyor.</p>
                </template>

                <div class="space-y-2">
                    <template x-for="(evt, i) in getSelectedEvents()" :key="i">
                        <a :href="'/staff/courses/' + evt.course_id"
                           class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                            <div class="w-1 h-10 rounded-full flex-shrink-0" :style="'background-color:' + evt.color"></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 truncate" x-text="evt.title"></p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-xs px-1.5 py-0.5 rounded-full"
                                          :class="evt.type === 'start' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300' : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300'"
                                          x-text="evt.type === 'start' ? 'Başlangıç' : 'Son Tarih'"></span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400" x-text="evt.category"></span>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-primary-500 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </template>
                </div>
            </div>
        </div>
    </div>

    {{-- Upcoming Deadlines --}}
    @if($upcoming->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="h-1.5 bg-gradient-to-r from-amber-400 to-amber-600"></div>
        <div class="p-5">
            <h3 class="text-sm font-bold text-gray-800 dark:text-white mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Yaklaşan Son Tarihler
            </h3>

            <div class="space-y-2">
                @foreach($upcoming as $enrollment)
                <a href="{{ route('staff.courses.show', $enrollment->course_id) }}"
                   class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                    <div class="w-1 h-10 rounded-full flex-shrink-0" style="background-color: {{ $enrollment->course->category?->color ?? '#14B8A6' }}"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 truncate">{{ $enrollment->course->title }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $enrollment->course->category?->name ?? 'Genel' }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-xs font-medium {{ $enrollment->course->end_date->diffInDays(now()) <= 3 ? 'text-red-600 dark:text-red-400' : 'text-amber-600 dark:text-amber-400' }}">
                            {{ $enrollment->course->end_date->diffInDays(now()) }} gün kaldı
                        </p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $enrollment->course->end_date->format('d.m.Y') }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Legend --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
        <div class="flex flex-wrap items-center gap-4 text-xs text-gray-600 dark:text-gray-400">
            <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Başlangıç Tarihi
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Son Tarih
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-6 h-6 rounded-md bg-primary-500 text-white text-xs flex items-center justify-center font-bold">{{ now()->day }}</span> Bugün
            </span>
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
            return parseInt(parts[2]) + ' ' + this.monthNames[parseInt(parts[1]) - 1] + ' ' + parts[0];
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
