<?php

namespace Modules\ExceptionSender\Handler;

use Illuminate\Support\Facades\Http;

class Logger
{
    public static function send($message)
    {
        $botToken = config('exception-sender.telegram.bot_token');
        $chatId = config('exception-sender.telegram.chat_id');

        // Send the message to Telegram channel
        $response = Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML',
        ]);
    }
}