<?php

namespace App\Console\Commands;

use App\Events\CallbackNotificationEvent;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CallbackLeadCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'callback:lead-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification to lead agent';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentDateTime = Carbon::now();

        $leads = Lead::query()
            ->whereNotNull('callback_at')
            ->where('notified', 0)
            ->where('callback_at', '<', $currentDateTime)
            ->orWhereNull('notified')
            ->get();
        
        foreach ($leads as $lead) {
            event(new CallbackNotificationEvent($lead));
        }

    }
}
