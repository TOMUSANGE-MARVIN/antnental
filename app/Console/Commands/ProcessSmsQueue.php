<?php

namespace App\Console\Commands;

use App\Services\SmsService;
use Illuminate\Console\Command;

class ProcessSmsQueue extends Command
{
    protected $signature = 'sms:process {--limit=100 : Maximum number of SMS to process} {--retry : Also retry failed SMS}';
    protected $description = 'Process pending SMS notifications from the queue';

    public function handle(SmsService $sms): int
    {
        $limit = (int) $this->option('limit');
        $retry = $this->option('retry');

        $this->info("Processing SMS queue (limit: {$limit})...");

        // Process pending notifications
        $result = $sms->processPendingNotifications($limit);
        
        $this->info("Processed {$result['total']} pending SMS notifications:");
        $this->info("✓ Successfully sent: {$result['success']}");
        if ($result['failed'] > 0) {
            $this->error("✗ Failed to send: {$result['failed']}");
        }

        // Retry failed notifications if requested
        if ($retry) {
            $this->info("\nRetrying failed notifications...");
            $retryResult = $sms->retryFailedNotifications(50);
            
            $this->info("Retried {$retryResult['total']} failed SMS notifications:");
            $this->info("✓ Successfully sent on retry: {$retryResult['success']}");
            if ($retryResult['failed'] > 0) {
                $this->error("✗ Still failed: {$retryResult['failed']}");
            }
        }

        return self::SUCCESS;
    }
}
