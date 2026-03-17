<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            background: #FFFBEB;
            width: 100%;
            height: 100%;
        }

        .certificate {
            position: relative;
            width: 100%;
            height: 100%;
            padding: 40px;
        }

        /* Outer border */
        .border-outer {
            border: 3px solid #B45309;
            padding: 8px;
            height: 100%;
        }

        /* Inner border */
        .border-inner {
            border: 1px solid #D97706;
            padding: 40px 50px;
            height: 100%;
            text-align: center;
            position: relative;
        }

        /* Corner ornaments */
        .corner {
            position: absolute;
            width: 50px;
            height: 50px;
            border-color: #B45309;
        }
        .corner-tl { top: 8px; left: 8px; border-top: 3px solid; border-left: 3px solid; }
        .corner-tr { top: 8px; right: 8px; border-top: 3px solid; border-right: 3px solid; }
        .corner-bl { bottom: 8px; left: 8px; border-bottom: 3px solid; border-left: 3px solid; }
        .corner-br { bottom: 8px; right: 8px; border-bottom: 3px solid; border-right: 3px solid; }

        .institution {
            font-size: 13px;
            letter-spacing: 3px;
            color: #92400E;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .title {
            font-size: 38px;
            font-weight: bold;
            color: #B45309;
            margin-bottom: 5px;
            letter-spacing: 2px;
        }

        .subtitle {
            font-size: 13px;
            color: #78716C;
            margin-bottom: 30px;
            letter-spacing: 1px;
        }

        .divider {
            width: 120px;
            height: 2px;
            background: linear-gradient(to right, transparent, #D97706, transparent);
            margin: 0 auto 25px;
        }

        .presented-to {
            font-size: 11px;
            color: #78716C;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 8px;
        }

        .recipient-name {
            font-size: 30px;
            font-weight: bold;
            color: #1C1917;
            margin-bottom: 6px;
        }

        .recipient-title {
            font-size: 12px;
            color: #78716C;
            margin-bottom: 20px;
        }

        .divider-sm {
            width: 80px;
            height: 1px;
            background: #D97706;
            margin: 0 auto 20px;
        }

        .description {
            font-size: 12px;
            color: #44403C;
            line-height: 1.6;
            max-width: 500px;
            margin: 0 auto 25px;
        }

        .course-name {
            font-weight: bold;
            color: #B45309;
        }

        /* Info grid */
        .info-grid {
            width: 100%;
            margin-bottom: 25px;
        }

        .info-grid td {
            padding: 4px 15px;
            font-size: 10px;
            color: #78716C;
        }

        .info-grid .value {
            font-size: 12px;
            font-weight: bold;
            color: #1C1917;
        }

        /* Score badge */
        .score-badge {
            display: inline-block;
            background: #B45309;
            color: #fff;
            font-size: 18px;
            font-weight: bold;
            padding: 8px 25px;
            border-radius: 20px;
            margin-bottom: 20px;
        }

        /* Footer */
        .footer {
            position: absolute;
            bottom: 25px;
            left: 50px;
            right: 50px;
        }

        .footer-grid {
            width: 100%;
        }

        .footer-grid td {
            width: 33.33%;
            text-align: center;
            vertical-align: bottom;
            padding: 0 20px;
        }

        .signature-line {
            border-top: 1px solid #D6D3D1;
            padding-top: 5px;
            font-size: 10px;
            color: #78716C;
        }

        .cert-number {
            font-size: 9px;
            color: #A8A29E;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="border-outer">
            <div class="border-inner">
                {{-- Corner ornaments --}}
                <div class="corner corner-tl"></div>
                <div class="corner corner-tr"></div>
                <div class="corner corner-bl"></div>
                <div class="corner corner-br"></div>

                {{-- Header --}}
                <div class="institution">{{ $settings['company_name'] ?? 'Devakent Belediyesi' }}</div>
                <div class="title">BAŞARI SERTİFİKASI</div>
                <div class="subtitle">Eğitim Tamamlama Belgesi</div>

                <div class="divider"></div>

                {{-- Recipient --}}
                <div class="presented-to">Bu belge</div>
                <div class="recipient-name">{{ $certificate->user->full_name }}</div>
                <div class="recipient-title">{{ $certificate->user->title ?? 'Personel' }} — {{ $certificate->user->department?->name ?? '' }}</div>

                <div class="divider-sm"></div>

                {{-- Description --}}
                <div class="description">
                    <span class="course-name">"{{ $certificate->course->title }}"</span>
                    eğitimini başarıyla tamamladığını belgeler.
                </div>

                {{-- Score --}}
                <div class="score-badge">%{{ $certificate->final_score }} Başarı</div>

                {{-- Info --}}
                <table class="info-grid" align="center">
                    <tr>
                        <td>Sertifika No<br><span class="value">{{ $certificate->certificate_number }}</span></td>
                        <td>Veriliş Tarihi<br><span class="value">{{ $certificate->issued_at->format('d.m.Y') }}</span></td>
                        <td>Kategori<br><span class="value">{{ $certificate->course->category?->name ?? 'Genel' }}</span></td>
                    </tr>
                </table>

                {{-- Footer with signatures --}}
                <div class="footer">
                    <table class="footer-grid">
                        <tr>
                            <td>
                                <div class="signature-line">Eğitim Sorumlusu</div>
                            </td>
                            <td>
                                <div class="cert-number">{{ $certificate->certificate_number }}</div>
                            </td>
                            <td>
                                <div class="signature-line">Kurum Yetkilisi</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
