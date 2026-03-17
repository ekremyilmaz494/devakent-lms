<?php

namespace App\Jobs;

use App\Models\CourseVideo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class TranscodeVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $timeout = 1800; // 30 min

    public function __construct(
        public int $courseVideoId
    ) {}

    public function handle(): void
    {
        $courseVideo = CourseVideo::find($this->courseVideoId);
        if (!$courseVideo || !$courseVideo->video_path) {
            return;
        }

        $sourcePath = Storage::disk('local')->path($courseVideo->video_path);
        if (!file_exists($sourcePath)) {
            Log::error("TranscodeVideoJob: Source not found: {$sourcePath}");
            return;
        }

        // Output directory for HLS segments
        $hlsDir = 'streams/' . $courseVideo->id;
        $outputDir = Storage::disk('local')->path($hlsDir);

        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $playlistPath = $outputDir . '/playlist.m3u8';
        $segmentPattern = $outputDir . '/segment_%03d.ts';

        // FFmpeg HLS transcoding via Laravel Process (safe, no shell injection)
        $ffmpeg = config('services.ffmpeg.path', 'ffmpeg');

        $result = Process::timeout(1800)->run([
            $ffmpeg, '-i', $sourcePath,
            '-c:v', 'libx264', '-preset', 'fast', '-crf', '23',
            '-c:a', 'aac', '-b:a', '128k',
            '-hls_time', '10', '-hls_list_size', '0',
            '-hls_segment_filename', $segmentPattern,
            '-f', 'hls', $playlistPath,
        ]);

        if (!$result->successful()) {
            Log::error("TranscodeVideoJob: FFmpeg failed", [
                'video_id' => $this->courseVideoId,
                'exit_code' => $result->exitCode(),
                'stderr' => substr($result->errorOutput(), -500),
            ]);
            $courseVideo->update(['transcode_status' => 'failed']);
            return;
        }

        // Get video duration via ffprobe
        $probeResult = Process::timeout(30)->run([
            str_replace('ffmpeg', 'ffprobe', $ffmpeg),
            '-v', 'error',
            '-show_entries', 'format=duration',
            '-of', 'default=noprint_wrappers=1:nokey=1',
            $sourcePath,
        ]);

        $updates = [
            'hls_path' => $hlsDir . '/playlist.m3u8',
            'transcode_status' => 'completed',
        ];

        if ($probeResult->successful()) {
            $duration = (int) round((float) trim($probeResult->output()));
            if ($duration > 0) {
                $updates['video_duration_seconds'] = $duration;
            }
        }

        $courseVideo->update($updates);
        Log::info("TranscodeVideoJob: Completed", ['video_id' => $this->courseVideoId]);
    }

    public function failed(\Throwable $exception): void
    {
        $courseVideo = CourseVideo::find($this->courseVideoId);
        $courseVideo?->update(['transcode_status' => 'failed']);

        Log::error("TranscodeVideoJob: Failed", [
            'video_id' => $this->courseVideoId,
            'error' => $exception->getMessage(),
        ]);
    }
}
