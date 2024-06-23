<?php

namespace Modules\ExceptionSender\Handler;

use Illuminate\Support\Facades\Http;
use Throwable;

class TelegramHandler
{
    public function handle(Throwable $exception): void
    {
//        // Set up your Telegram bot details
//        $botToken = config('exception-sender.telegram.bot_token');
//        $chatId = config('exception-sender.telegram.chat_id');
//
//        $errorMessage = $exception->getMessage();
//        $errLine = $exception->getLine();
//        $errFile = $exception->getFile();
//        $current_url = url()->current();
//
//        $clientIp = request()->getClientIp();
//        $clientPort = request()->getPort();
//        $method = request()->getMethod();
//
//        $requestPayload = json_encode((request()->getPayload() ?? []));
//        $user = "without auth";
//
//        $message = "An error occurred:\n\n<b>$errorMessage</b>\n\n <b>Project BUILDER</b>\n\n In $errFile -> $errLine \n\n $user \n <b>Url:</b> $current_url \n <b>ClientIp:</b> $clientIp \n <b>Payload:</b>$requestPayload \n <b>Method:</b>$method \n <b>Port:</b>$clientPort";
//
//        $response = Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
//            'chat_id' => $chatId,
//            'text' => $message,
//            'parse_mode' => 'HTML',
//        ]);
    }
}
