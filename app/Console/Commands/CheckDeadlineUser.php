<?php

namespace App\Console\Commands;

use App\Models\Lead;
use App\Models\User;
use App\Notifications\DeadlineLeadNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class CheckDeadlineUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-deadline-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check deadline user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentDateTime = Carbon::now();
        $twoHoursAgo = $currentDateTime->subHours(2);
        $halfHourAgo = $currentDateTime->subMinutes(30);

        $oneHourThirtyMinutesAgo = $currentDateTime->addMinutes(30);

        $leads = Lead::query()
            ->where('company_id', 1)
            ->where('created_at', '<', $halfHourAgo)
            ->get();

        foreach ($leads as $lead) {
            Notification::send(User::allAdmins(1), new DeadlineLeadNotification($lead));
        }

    }
}
