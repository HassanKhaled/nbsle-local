<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WorkReg;
use App\Jobs\SendWorkshopEmailJob;

class QueueWorkshopEmails extends Command
{
    protected $signature = 'emails:queue-workshop {workshop_id} {batch=10}';
    protected $description = 'Queue workshop emails in batches';

    public function handle()
    {
        $workshop_id = $this->argument('workshop_id');
        $batchSize = $this->argument('batch');

        $users = WorkReg::where('workshop_id', $workshop_id)
                        ->where('email_sent', false)
                        ->limit($batchSize)
                        ->get();

        if ($users->isEmpty()) {
            $this->info('No pending emails to queue.');
            return;
        }

        foreach ($users as $user) {
            SendWorkshopEmailJob::dispatch(
                $user->email,
                $workshop_id,
                'Workshop Notification', // or dynamic title
                'Hello, this is your workshop notification.', // or dynamic body
                [] // you can pass attachments if stored
            )->onQueue('workshop-emails');
        }

        $this->info("Queued {$users->count()} emails for workshop {$workshop_id}");
    }
}
