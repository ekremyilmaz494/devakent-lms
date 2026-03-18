<?php

namespace App\Exports;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CourseExport implements FromQuery, WithChunkReading, WithHeadings, WithMapping, WithTitle, WithStyles
{
    public function chunkSize(): int { return 500; }
    public function __construct(
        private string $search = '',
        private string $filterCategory = '',
        private string $filterStatus = '',
        private string $filterMandatory = '',
        private array $selectedIds = [],
    ) {}

    public function query()
    {
        return Course::query()
            ->with(['category', 'departments'])
            ->withCount(['questions', 'enrollments', 'enrollments as completed_enrollments_count' => fn ($q) => $q->where('status', 'completed')])
            ->when(!empty($this->selectedIds), fn ($q) => $q->whereIn('id', $this->selectedIds))
            ->when(empty($this->selectedIds), function ($q) {
                $q->when($this->search, fn ($q2) => $q2->where('title', 'like', "%{$this->search}%"))
                  ->when($this->filterCategory, fn ($q2) => $q2->where('category_id', $this->filterCategory))
                  ->when($this->filterStatus, fn ($q2) => $q2->where('status', $this->filterStatus))
                  ->when($this->filterMandatory !== '', function ($q2) {
                      $q2->where('is_mandatory', $this->filterMandatory === '1');
                  });
            })
            ->orderByDesc('created_at');
    }

    public function headings(): array
    {
        return ['Eğitim Adı', 'Kategori', 'Durum', 'Zorunlu', 'Soru Sayısı', 'Kayıt Sayısı', 'Tamamlayan', 'Tamamlanma %', 'Süre (dk)', 'Departmanlar', 'Başlangıç', 'Bitiş', 'Son Güncelleme'];
    }

    public function map($course): array
    {
        $completionPct = $course->enrollments_count > 0
            ? round($course->completed_enrollments_count / $course->enrollments_count * 100)
            : 0;

        return [
            $course->title,
            $course->category?->name ?? '—',
            $course->status === 'published' ? 'Yayında' : 'Taslak',
            $course->is_mandatory ? 'Evet' : 'Hayır',
            $course->questions_count,
            $course->enrollments_count,
            $course->completed_enrollments_count,
            '%' . $completionPct,
            $course->exam_duration_minutes ?? '—',
            $course->departments->pluck('name')->join(', ') ?: '—',
            $course->start_date?->format('d.m.Y') ?? '—',
            $course->end_date?->format('d.m.Y') ?? '—',
            $course->updated_at?->format('d.m.Y H:i') ?? '—',
        ];
    }

    public function title(): string
    {
        return 'Eğitimler';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 11]],
        ];
    }
}
