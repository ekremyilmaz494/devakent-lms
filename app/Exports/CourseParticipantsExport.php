<?php

namespace App\Exports;

use App\Models\Enrollment;
use App\Models\ExamAttempt;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CourseParticipantsExport implements FromQuery, WithChunkReading, WithHeadings, WithMapping, WithTitle, WithStyles
{
    public function chunkSize(): int { return 500; }
    private static array $statusLabels = [
        'not_started' => 'Başlamadı',
        'in_progress' => 'Devam Ediyor',
        'completed'   => 'Tamamlandı',
        'failed'      => 'Başarısız',
    ];

    public function __construct(
        private int    $courseId,
        private string $departmentFilter = '',
        private string $statusFilter     = '',
        private string $search           = '',
    ) {}

    public function query()
    {
        $preSub = ExamAttempt::select('score')
            ->whereColumn('enrollment_id', 'enrollments.id')
            ->where('exam_type', 'pre_exam')
            ->whereNotNull('finished_at')
            ->orderByDesc('finished_at')
            ->limit(1);

        $postSub = ExamAttempt::select('score')
            ->whereColumn('enrollment_id', 'enrollments.id')
            ->where('exam_type', 'post_exam')
            ->whereNotNull('finished_at')
            ->orderByDesc('finished_at')
            ->limit(1);

        return Enrollment::query()
            ->where('course_id', $this->courseId)
            ->with(['user.department'])
            ->addSelect([
                'enrollments.*',
                'pre_score'  => $preSub,
                'post_score' => $postSub,
            ])
            ->when($this->search, fn ($q) =>
                $q->whereHas('user', fn ($u) =>
                    $u->where('first_name', 'like', "%{$this->search}%")
                      ->orWhere('last_name',  'like', "%{$this->search}%")
                )
            )
            ->when($this->departmentFilter, fn ($q) =>
                $q->whereHas('user', fn ($u) => $u->where('department_id', $this->departmentFilter))
            )
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->orderBy('enrollments.created_at');
    }

    public function headings(): array
    {
        return [
            'Ad Soyad',
            'Departman',
            'Kayıt Tarihi',
            'Durum',
            'Tamamlanma Tarihi',
            'Ön Sınav (%)',
            'Son Sınav (%)',
            'Gelişim (%)',
        ];
    }

    public function map($enrollment): array
    {
        $preScore  = $enrollment->pre_score  !== null ? (float) $enrollment->pre_score  : null;
        $postScore = $enrollment->post_score !== null ? (float) $enrollment->post_score : null;
        $improvement = ($preScore !== null && $postScore !== null)
            ? round($postScore - $preScore, 1)
            : null;

        return [
            trim(($enrollment->user?->first_name ?? '') . ' ' . ($enrollment->user?->last_name ?? '')),
            $enrollment->user?->department?->name ?? '—',
            $enrollment->created_at->format('d.m.Y'),
            self::$statusLabels[$enrollment->status] ?? $enrollment->status,
            $enrollment->completed_at?->format('d.m.Y') ?? '—',
            $preScore  !== null ? number_format($preScore, 1)  : '—',
            $postScore !== null ? number_format($postScore, 1) : '—',
            $improvement !== null ? ($improvement >= 0 ? '+' : '') . number_format($improvement, 1) : '—',
        ];
    }

    public function title(): string
    {
        return 'Katılımcılar';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 11]],
        ];
    }
}
