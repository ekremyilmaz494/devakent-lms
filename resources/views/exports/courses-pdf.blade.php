<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Eğitim Listesi</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; }
        h1 { font-size: 18px; color: #92400e; margin-bottom: 4px; }
        .subtitle { font-size: 11px; color: #78716c; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th { background: #92400e; color: #fff; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; padding: 6px 8px; text-align: left; }
        td { padding: 5px 8px; border-bottom: 1px solid #e7e5e4; font-size: 10px; }
        tr:nth-child(even) td { background: #fafaf9; }
        .badge { display: inline-block; padding: 1px 6px; border-radius: 4px; font-size: 9px; font-weight: bold; }
        .published { background: #d1fae5; color: #065f46; }
        .draft { background: #e7e5e4; color: #57534e; }
        .mandatory { background: #fee2e2; color: #991b1b; }
        .footer { margin-top: 16px; font-size: 9px; color: #a8a29e; text-align: right; }
    </style>
</head>
<body>
    <h1>Devakent LMS - Eğitim Listesi</h1>
    <div class="subtitle">{{ now()->format('d.m.Y H:i') }} tarihinde oluşturuldu · {{ $courses->count() }} eğitim</div>

    <table>
        <thead>
            <tr>
                <th>Eğitim Adı</th>
                <th>Kategori</th>
                <th>Durum</th>
                <th>Soru</th>
                <th>Kayıt</th>
                <th>Tamamlayan</th>
                <th>Departmanlar</th>
                <th>Tarih Aralığı</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courses as $course)
                <tr>
                    <td>
                        <strong>{{ $course->title }}</strong>
                        @if($course->is_mandatory)
                            <span class="badge mandatory">Zorunlu</span>
                        @endif
                    </td>
                    <td>{{ $course->category?->name ?? '—' }}</td>
                    <td>
                        <span class="badge {{ $course->status === 'published' ? 'published' : 'draft' }}">
                            {{ $course->status === 'published' ? 'Yayında' : 'Taslak' }}
                        </span>
                    </td>
                    <td style="text-align:center">{{ $course->questions_count }}</td>
                    <td style="text-align:center">{{ $course->enrollments_count }}</td>
                    <td style="text-align:center">{{ $course->completed_enrollments_count }}</td>
                    <td>{{ $course->departments->pluck('name')->join(', ') ?: '—' }}</td>
                    <td>
                        @if($course->start_date && $course->end_date)
                            {{ $course->start_date->format('d.m.Y') }} — {{ $course->end_date->format('d.m.Y') }}
                        @else
                            —
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">Devakent LMS &copy; {{ date('Y') }}</div>
</body>
</html>
