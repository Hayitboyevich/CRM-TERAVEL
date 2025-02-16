<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CallbackNotificationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public $lead)
    {
    }
}
