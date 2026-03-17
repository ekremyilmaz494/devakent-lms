<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CourseParticipantsExport;
use App\Exports\CourseResultsExport;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\SystemSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CourseExportController extends Controller
{
    public function participants(Course $course, Request $request)
    {
        $export = new CourseParticipantsExport(
            courseId:         $course->id,
            departmentFilter: $request->get('department', ''),
            statusFilter:     $request->get('status', ''),
            search:           $request->get('search', ''),
        );

        $filename = 'katilimcilar-' . $course->id . '-' . date('Y-m-d') . '.xlsx';

        return Excel::download($export, $filename);
    }

    public function results(Course $course, Request $request)
    {
        $departmentFilter = $request->get('department', '');
        $examFilter       = $request->get('exam_filter', '');
        $format           = $request->get('format', 'excel');

        $export = new CourseResultsExport(
            courseId:         $course->id,
            departmentFilter: $departmentFilter,
            examFilter:       $examFilter,
        );

        if ($format === 'pdf') {
            $institution = SystemSetting::get('institution_name', 'Devakent Hastanesi');

            $pdf = Pdf::loadView('pdf.course-results', [
                'course'      => $course,
                'headings'    => $export->headings(),
                'rows'        => $export->array(),
                'institution' => $institution,
                'generatedAt' => now()->format('d.m.Y H:i'),
            ])->setPaper('a4', 'landscape');

            $filename = 'sinav-sonuclari-' . $course->id . '-' . date('Y-m-d') . '.pdf';

            return response()->streamDownload(
                fn () => print($pdf->output()),
                $filename
            );
        }

        $filename = 'sinav-sonuclari-' . $course->id . '-' . date('Y-m-d') . '.xlsx';

        return Excel::download($export, $filename);
    }
}
