<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Personel Listesi</title>
    <style>
        @page { size: A4 landscape; margin: 15mm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; }
        h1 { font-size: 18px; color: #92400e; margin-bottom: 4px; }
        .subtitle { font-size: 11px; color: #78716c; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th { background: #92400e; color: #fff; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; padding: 6px 8px; text-align: left; }
        td { padding: 5px 8px; border-bottom: 1px solid #e7e5e4; font-size: 10px; }
        tr:nth-child(even) td { background: #fafaf9; }
        .badge { display: inline-block; padding: 1px 6px; border-radius: 4px; font-size: 9px; font-weight: bold; }
        .active { background: #d1fae5; color: #065f46; }
        .inactive { background: #fee2e2; color: #991b1b; }
        .footer { margin-top: 16px; font-size: 9px; color: #a8a29e; text-align: right; }
    </style>
</head>
<body>
    <h1>{{ $institution }} - Personel Listesi</h1>
    <div class="subtitle">{{ now()->format('d.m.Y H:i') }} tarihinde oluşturuldu · {{ $staff->count() }} personel</div>

    <table>
        <thead>
            <tr>
                <th>Ad Soyad</th>
                <th>E-posta</th>
                <th>Sicil No</th>
                <th>Departman</th>
                <th>Ünvan</th>
                <th>İlerleme</th>
                <th>Son Giriş</th>
                <th>Durum</th>
            </tr>
        </thead>
        <tbody>
            @foreach($staff as $user)
                @php
                    $progress = $user->enrollments_count > 0
                        ? round($user->completed_enrollments_count / $user->enrollments_count * 100)
                        : 0;
                @endphp
                <tr>
                    <td><strong>{{ $user->full_name }}</strong></td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->registration_number ?? '—' }}</td>
                    <td>{{ $user->department?->name ?? '—' }}</td>
                    <td>{{ $user->title ?? '—' }}</td>
                    <td style="text-align:center">%{{ $progress }} ({{ $user->completed_enrollments_count }}/{{ $user->enrollments_count }})</td>
                    <td>{{ $user->last_login_at?->format('d.m.Y H:i') ?? 'Henüz giriş yok' }}</td>
                    <td>
                        <span class="badge {{ $user->is_active ? 'active' : 'inactive' }}">
                            {{ $user->is_active ? 'Aktif' : 'Pasif' }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">{{ $institution }} &copy; {{ date('Y') }}</div>
</body>
</html>
