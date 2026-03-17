<?php

namespace App\Http\Controllers;

use App\Models\CourseVideo;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class StreamController extends Controller
{
    /**
     * Serve HLS playlist (.m3u8) for authenticated users.
     */
    public function playlist(CourseVideo $courseVideo): Response
    {
        if (!$courseVideo->isHlsReady()) {
            abort(404, 'HLS stream not available');
        }

        $path = Storage::disk('local')->path($courseVideo->hls_path);
        abort_unless(file_exists($path), 404);

        return response()->file($path, [
            'Content-Type' => 'application/vnd.apple.mpegurl',
            'Cache-Control' => 'no-cache',
        ]);
    }

    /**
     * Serve HLS segment (.ts) files for authenticated users.
     */
    public function segment(CourseVideo $courseVideo, string $filename): Response
    {
        // Only allow .ts files with safe names
        if (!preg_match('/^segment_\d{3}\.ts$/', $filename)) {
            abort(404);
        }

        $dir = dirname($courseVideo->hls_path ?? '');
        $path = Storage::disk('local')->path($dir . '/' . $filename);
        abort_unless(file_exists($path), 404);

        return response()->file($path, [
            'Content-Type' => 'video/mp2t',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
