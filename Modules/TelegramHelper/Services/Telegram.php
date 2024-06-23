<?php

namespace Modules\TelegramHelper\Services;

use Illuminate\Support\Facades\Http;

class Telegram
{
    public static function sendMessage($message): string
    {
        $botToken = config('telegramhelper.bot_token');
        $chatId = config('telegramhelper.chat_id');
        
        $response = Http::post('https://api.telegram.org/bot' . $botToken . '/sendMessage', [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML',
        ]);

        return $response->body();
    }

    public function updateMessage()
    {
    }

}