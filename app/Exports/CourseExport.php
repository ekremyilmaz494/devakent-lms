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
    ) {}

    public function query()
    {
        return Course::query()
            ->with(['category', 'departments'])
            ->withCount(['questions', 'enrollments', 'enrollments as completed_enrollments_count' => fn ($q) => $q->where('status', 'completed')])
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->filterCategory, fn ($q) => $q->where('category_id', $this->filterCategory))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterMandatory !== '', function ($q) {
                $q->where('is_mandatory', $this->filterMandatory === '1');
            })
            ->orderByDesc('created_at');
    }

    public function headings(): array
    {
        return ['Eğitim Adı', 'Kategori', 'Durum', 'Zorunlu', 'Soru Sayısı', 'Kayıt Sayısı', 'Tamamlayan', 'Departmanlar', 'Başlangıç', 'Bitiş'];
    }

    public function map($course): array
    {
        return [
            $course->title,
            $course->category?->name ?? '—',
            $course->status === 'published' ? 'Yayında' : 'Taslak',
            $course->is_mandatory ? 'Evet' : 'Hayır',
            $course->questions_count,
            $course->enrollments_count,
            $course->completed_enrollments_count,
            $course->departments->pluck('name')->join(', ') ?: '—',
            $course->start_date?->format('d.m.Y') ?? '—',
            $course->end_date?->format('d.m.Y') ?? '—',
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
