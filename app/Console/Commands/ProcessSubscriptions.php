<?php

namespace App\Console\Commands;

use App\Services\SubscriptionService;
use Illuminate\Console\Command;

class ProcessSubscriptions extends Command
{
    protected $signature = 'subscriptions:process';

    protected $description = 'Expire subscriptions past their access window and send renewal reminders for those expiring soon.';

    public function handle(SubscriptionService $subscriptions): int
    {
        $expired = $subscriptions->expireDueSubscriptions();
        $this->info("Expired {$expired} subscription(s).");

        $reminded = $subscriptions->sendRenewalReminders(3);
        $this->info("Sent {$reminded} renewal reminder(s).");

        return self::SUCCESS;
    }
}
