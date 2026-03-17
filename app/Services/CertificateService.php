<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\SystemSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class CertificateService
{
    public function generatePdf(Certificate $certificate): string
    {
        $certificate->load(['user.department', 'course.category']);

        $settings = [
            'company_name' => SystemSetting::get('institution_name', 'Devakent Hastanesi'),
        ];

        $pdf = Pdf::loadView('pdf.certificate', compact('certificate', 'settings'))
            ->setPaper('a4', 'landscape')
            ->setOption('isRemoteEnabled', true)
            ->setOption('defaultFont', 'DejaVu Sans');

        $directory = 'certificates';
        Storage::disk('local')->makeDirectory($directory);

        $filename = $directory . '/' . $certificate->certificate_number . '.pdf';
        Storage::disk('local')->put($filename, $pdf->output());

        $certificate->update(['pdf_path' => $filename]);

        return $filename;
    }
}
