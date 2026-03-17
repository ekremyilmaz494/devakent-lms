<?php

namespace App\Services;

use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HrIntegrationService
{
    private string $baseUrl;
    private string $apiToken;
    private bool $enabled;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.hr.base_url', ''), '/');
        $this->apiToken = config('services.hr.api_token', '');
        $this->enabled = config('services.hr.enabled', false);
    }

    public function isEnabled(): bool
    {
        return $this->enabled && !empty($this->baseUrl) && !empty($this->apiToken);
    }

    /**
     * Sync staff from HR system to LMS.
     * Returns array with counts of created, updated, and deactivated users.
     */
    public function syncStaffFromHr(): array
    {
        if (!$this->isEnabled()) {
            return ['error' => 'HR integration is not configured'];
        }

        $result = ['created' => 0, 'updated' => 0, 'deactivated' => 0, 'errors' => []];

        try {
            $response = Http::withToken($this->apiToken)
                ->timeout(30)
                ->get("{$this->baseUrl}/api/employees");

            if (!$response->successful()) {
                Log::error('HR Sync: API request failed', ['status' => $response->status()]);
                return ['error' => 'HR API returned status ' . $response->status()];
            }

            $employees = $response->json('data', []);
            $hrIds = [];

            foreach ($employees as $emp) {
                try {
                    $hrIds[] = $emp['employee_id'] ?? null;
                    $this->syncEmployee($emp, $result);
                } catch (\Throwable $e) {
                    $result['errors'][] = ($emp['email'] ?? 'unknown') . ': ' . $e->getMessage();
                }
            }

            // Deactivate users no longer in HR (optional)
            if (!empty($hrIds)) {
                $deactivated = User::role('staff')
                    ->whereNotNull('hr_employee_id')
                    ->whereNotIn('hr_employee_id', array_filter($hrIds))
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
                $result['deactivated'] = $deactivated;
            }

        } catch (\Throwable $e) {
            Log::error('HR Sync: Exception', ['error' => $e->getMessage()]);
            return ['error' => $e->getMessage()];
        }

        Log::info('HR Sync completed', $result);
        return $result;
    }

    /**
     * Send training completion data back to HR system.
     */
    public function sendCompletionToHr(User $user, string $courseName, string $completionDate, ?int $score = null): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        if (!$user->hr_employee_id) {
            return false;
        }

        try {
            $response = Http::withToken($this->apiToken)
                ->timeout(15)
                ->post("{$this->baseUrl}/api/training-records", [
                    'employee_id' => $user->hr_employee_id,
                    'course_name' => $courseName,
                    'completion_date' => $completionDate,
                    'score' => $score,
                    'source' => 'devakent-lms',
                ]);

            if ($response->successful()) {
                Log::info('HR: Completion sent', ['user' => $user->id, 'course' => $courseName]);
                return true;
            }

            Log::warning('HR: Failed to send completion', [
                'user' => $user->id,
                'status' => $response->status(),
            ]);
        } catch (\Throwable $e) {
            Log::error('HR: Send completion error', ['error' => $e->getMessage()]);
        }

        return false;
    }

    /**
     * Sync a single employee record.
     */
    private function syncEmployee(array $emp, array &$result): void
    {
        $email = $emp['email'] ?? null;
        if (!$email) {
            return;
        }

        // Find or match department
        $department = null;
        if (!empty($emp['department'])) {
            $department = Department::firstOrCreate(
                ['name' => $emp['department']],
                ['is_active' => true]
            );
        }

        $userData = [
            'first_name' => $emp['first_name'] ?? '',
            'last_name' => $emp['last_name'] ?? '',
            'phone' => $emp['phone'] ?? null,
            'title' => $emp['title'] ?? null,
            'registration_number' => $emp['employee_id'] ?? null,
            'hr_employee_id' => $emp['employee_id'] ?? null,
            'department_id' => $department?->id,
            'is_active' => ($emp['status'] ?? 'active') === 'active',
        ];

        $user = User::where('email', $email)->first();

        if ($user) {
            $user->update($userData);
            $result['updated']++;
        } else {
            $user = User::create(array_merge($userData, [
                'email' => $email,
                'password' => bcrypt('devakent2024!'),
            ]));
            $user->assignRole('staff');
            $result['created']++;
        }
    }
}
