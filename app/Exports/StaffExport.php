<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StaffExport implements FromQuery, WithChunkReading, WithHeadings, WithMapping, WithTitle, WithStyles
{
    public function chunkSize(): int { return 500; }
    public function __construct(
        private string $search = '',
        private string $filterDepartment = '',
        private string $filterStatus = '',
    ) {}

    public function query()
    {
        return User::query()
            ->role('staff')
            ->with('department')
            ->withCount([
                'enrollments',
                'enrollments as completed_enrollments_count' => fn ($q) => $q->where('status', 'completed'),
            ])
            ->when($this->search, function ($q) {
                $q->where(function ($q2) {
                    $q2->where('first_name', 'like', "%{$this->search}%")
                       ->orWhere('last_name', 'like', "%{$this->search}%")
                       ->orWhere('email', 'like', "%{$this->search}%")
                       ->orWhere('registration_number', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filterDepartment, fn ($q) => $q->where('department_id', $this->filterDepartment))
            ->when($this->filterStatus !== '', function ($q) {
                $q->where('is_active', $this->filterStatus === '1');
            })
            ->orderBy('first_name');
    }

    public function headings(): array
    {
        return ['Ad Soyad', 'E-posta', 'Sicil No', 'Departman', 'Ünvan', 'Telefon', 'İşe Giriş', 'Eğitim Kaydı', 'Tamamlanan', 'İlerleme %', 'Son Giriş', 'Durum'];
    }

    public function map($user): array
    {
        $progress = $user->enrollments_count > 0
            ? round($user->completed_enrollments_count / $user->enrollments_count * 100)
            : 0;

        return [
            $user->full_name,
            $user->email,
            $user->registration_number ?? '—',
            $user->department?->name ?? '—',
            $user->title ?? '—',
            $user->phone ?? '—',
            $user->hire_date?->format('d.m.Y') ?? '—',
            $user->enrollments_count,
            $user->completed_enrollments_count,
            '%' . $progress,
            $user->last_login_at?->format('d.m.Y H:i') ?? 'Henüz giriş yok',
            $user->is_active ? 'Aktif' : 'Pasif',
        ];
    }

    public function title(): string
    {
        return 'Personel Listesi';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 11]],
        ];
    }
}
