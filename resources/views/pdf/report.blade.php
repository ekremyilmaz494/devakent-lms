<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <style>
        @page { size: A4 landscape; margin: 20mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1C1917; }

        .header { text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #B45309; }
        .header h1 { font-size: 16px; color: #B45309; margin-bottom: 3px; }
        .header p { font-size: 10px; color: #78716C; }
        .header .date { font-size: 9px; color: #A8A29E; margin-top: 5px; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #FEF3C7; color: #92400E; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; padding: 8px 6px; text-align: left; border-bottom: 2px solid #D97706; }
        td { padding: 6px; border-bottom: 1px solid #E7E5E4; font-size: 10px; }
        tr:nth-child(even) td { background-color: #FAFAF9; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }

        .footer { margin-top: 20px; padding-top: 10px; border-top: 1px solid #D6D3D1; font-size: 8px; color: #A8A29E; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $institution }} — {{ $title }}</h1>
        <p>Eğitim Yönetim Sistemi Raporu</p>
        <div class="date">Oluşturulma Tarihi: {{ now()->format('d.m.Y H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                @foreach($headings as $heading)
                    <th>{{ $heading }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    @foreach($row as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ $institution }} — {{ now()->format('Y') }} &middot; Bu rapor otomatik olarak oluşturulmuştur.
    </div>
</body>
</html>
