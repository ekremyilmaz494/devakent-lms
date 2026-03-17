<?php

namespace App\Exports;

use App\Models\Enrollment;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CourseResultsExport implements FromArray, WithHeadings, WithTitle, WithStyles
{
    private array $rows;

    public function __construct(
        private int    $courseId,
        private string $departmentFilter = '',
        private string $examFilter       = '',
    ) {
        $this->rows = $this->buildRows();
    }

    private function buildRows(): array
    {
        $query = Enrollment::query()
            ->where('course_id', $this->courseId)
            ->with([
                'user.department',
                'examAttempts' => fn ($q) => $q->whereNotNull('finished_at')->orderBy('finished_at'),
            ])
            ->when($this->departmentFilter, fn ($q) =>
                $q->whereHas('user', fn ($u) => $u->where('department_id', $this->departmentFilter))
            );

        if ($this->examFilter === 'pre_only') {
            $query->whereHas('examAttempts', fn ($q) =>
                $q->where('exam_type', 'pre_exam')->whereNotNull('finished_at')
            );
        } elseif ($this->examFilter === 'post_only') {
            $query->whereHas('examAttempts', fn ($q) =>
                $q->where('exam_type', 'post_exam')->whereNotNull('finished_at')
            );
        } elseif ($this->examFilter === 'both') {
            $query->whereHas('examAttempts', fn ($q) =>
                $q->where('exam_type', 'pre_exam')->whereNotNull('finished_at')
            )->whereHas('examAttempts', fn ($q) =>
                $q->where('exam_type', 'post_exam')->whereNotNull('finished_at')
            );
        }

        return $query->get()->map(function ($enrollment) {
            $preAttempt  = $enrollment->examAttempts->where('exam_type', 'pre_exam')->sortByDesc('finished_at')->first();
            $postAttempt = $enrollment->examAttempts->where('exam_type', 'post_exam')->sortByDesc('finished_at')->first();

            $preScore  = $preAttempt  ? (float) $preAttempt->score  : null;
            $postScore = $postAttempt ? (float) $postAttempt->score : null;
            $diff      = ($preScore !== null && $postScore !== null) ? round($postScore - $preScore, 1) : null;

            return [
                trim(($enrollment->user?->first_name ?? '') . ' ' . ($enrollment->user?->last_name ?? '')),
                $enrollment->user?->department?->name ?? '—',
                $preAttempt?->finished_at?->format('d.m.Y H:i') ?? '—',
                $preScore  !== null ? number_format($preScore, 1)  : '—',
                $postAttempt?->finished_at?->format('d.m.Y H:i') ?? '—',
                $postScore !== null ? number_format($postScore, 1) : '—',
                $diff !== null ? ($diff >= 0 ? '+' : '') . number_format($diff, 1) : '—',
                $postAttempt ? ($postAttempt->is_passed ? 'Başarılı' : 'Başarısız') : '—',
            ];
        })->toArray();
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            'Ad Soyad',
            'Departman',
            'Ön Sınav Tarihi',
            'Ön Sınav Puanı (%)',
            'Son Sınav Tarihi',
            'Son Sınav Puanı (%)',
            'Fark (%)',
            'Durum',
        ];
    }

    public function title(): string
    {
        return 'Sınav Sonuçları';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 11]],
        ];
    }
}
