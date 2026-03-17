<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Services\CertificateService;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $certificates = $user->certificates()
            ->with(['course.category', 'enrollment'])
            ->orderByDesc('issued_at')
            ->get();

        $stats = [
            'total' => $certificates->count(),
            'this_year' => $certificates->filter(fn ($c) => $c->issued_at->year === now()->year)->count(),
            'avg_score' => $certificates->count() > 0 ? round($certificates->avg('final_score')) : 0,
        ];

        return view('staff.certificates', compact('certificates', 'stats'));
    }

    public function download(Certificate $certificate, CertificateService $service)
    {
        abort_unless($certificate->user_id === auth()->id(), 403);

        if (!$certificate->pdf_path || !Storage::disk('local')->exists($certificate->pdf_path)) {
            $service->generatePdf($certificate);
            $certificate->refresh();
        }

        return Storage::disk('local')->download(
            $certificate->pdf_path,
            $certificate->certificate_number . '.pdf'
        );
    }
}
