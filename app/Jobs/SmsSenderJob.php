<?php

namespace App\Jobs;

use App\Models\SmsMailing;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Mailing\Services\MailingService;

class SmsSenderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private MailingService $mailingService;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->mailingService = new MailingService();
    }

    /**
     * Execute the job.
     */

    public function handle(): void
    {
        $messages = SmsMailing::query()
            ->where(['status' => 'pending'])
            ->get();

        $userKeys = $messages->pluck('user_id')->toArray();
        $t = [];
        foreach ($userKeys as $userKey) {
            foreach (json_decode($userKey, true) as $u) {
                $t[] = json_decode($u, true);
            }
        }

        $users = User::query()
            ->whereIn('id', $t)
            ->get()
            ->pluck('mobile', 'id')
            ->toArray();

        foreach ($messages as $message) {
            if (Carbon::now()->diffInMinutes($message->delivery_date) > 0) {
                $user_ids = json_decode($message->user_id, true);
                foreach ($user_ids as $user_id) {
                    try {
                        $this->mailingService->send($users[$user_id], $message->message);
                    } catch (Exception $exception) {
                        dd($exception->getMessage());
                    }
                }
            }
            $message->status = 'success';
            $message->save();
        }
    }
}
