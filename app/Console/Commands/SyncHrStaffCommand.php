<?php

namespace App\Console\Commands;

use App\Services\HrIntegrationService;
use Illuminate\Console\Command;

class SyncHrStaffCommand extends Command
{
    protected $signature = 'hr:sync-staff';
    protected $description = 'Sync staff data from HR system to LMS';

    public function handle(HrIntegrationService $service): int
    {
        if (!$service->isEnabled()) {
            $this->warn('HR integration is not configured. Set HR_API_URL and HR_API_TOKEN in .env');
            return self::FAILURE;
        }

        $this->info('Starting HR staff sync...');

        $result = $service->syncStaffFromHr();

        if (isset($result['error'])) {
            $this->error('Sync failed: ' . $result['error']);
            return self::FAILURE;
        }

        $this->info("Sync completed:");
        $this->line("  Created: {$result['created']}");
        $this->line("  Updated: {$result['updated']}");
        $this->line("  Deactivated: {$result['deactivated']}");

        if (!empty($result['errors'])) {
            $this->warn('Errors (' . count($result['errors']) . '):');
            foreach (array_slice($result['errors'], 0, 10) as $error) {
                $this->line("  - {$error}");
            }
        }

        return self::SUCCESS;
    }
}
