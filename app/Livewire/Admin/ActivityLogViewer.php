<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class ActivityLogViewer extends AdminComponent
{
    use WithPagination;

    public string $search = '';
    public string $eventFilter = '';
    public string $subjectFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingEventFilter(): void
    {
        $this->resetPage();
    }

    public function updatingSubjectFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $activities = Activity::query()
            ->with(['causer', 'subject'])
            ->when($this->search, function ($q) {
                $q->where(function ($q) {
                    $q->where('description', 'like', "%{$this->search}%")
                      ->orWhereHasMorph('causer', [\App\Models\User::class], function ($q) {
                          $q->where('first_name', 'like', "%{$this->search}%")
                            ->orWhere('last_name', 'like', "%{$this->search}%")
                            ->orWhere('email', 'like', "%{$this->search}%");
                      });
                });
            })
            ->when($this->eventFilter, fn ($q) => $q->where('event', $this->eventFilter))
            ->when($this->subjectFilter, fn ($q) => $q->where('subject_type', $this->subjectFilter))
            ->latest()
            ->paginate(20);

        $langModelLabels = __('lms.activity_log_models');

        // Eskisi: her render'da tam tablo taraması
        // Yenisi: 1 saat cache — dil değişiminde cache'i temizle
        $subjectTypes = Cache::remember('activity_log.subject_types.' . app()->getLocale(), 3600, function () use ($langModelLabels) {
            return Activity::distinct()
                ->whereNotNull('subject_type')
                ->pluck('subject_type')
                ->mapWithKeys(fn ($type) => [$type => $langModelLabels[$type] ?? class_basename($type)])
                ->toArray();
        });

        return view('livewire.admin.activity-log-viewer', compact('activities', 'subjectTypes'));
    }
}
