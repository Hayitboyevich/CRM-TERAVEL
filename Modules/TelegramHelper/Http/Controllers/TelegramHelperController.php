<?php

namespace Modules\TelegramHelper\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Modules\TelegramHelper\Services\HistoryParser;

class TelegramHelperController extends Controller
{
    public function __construct(public HistoryParser $historyParser)
    {
    }

    public function webhook(Request $request)
    {
        $telegramData = $request->all();

        try {
            $message = Arr::get($telegramData, 'message');
            if (!$message) {
                $message - Arr::get($telegramData, 'channel_post');
            }
            $message = $message['text'];
            $error = '';
            $this->historyParser->parse($message);

        } catch (Exception $exception) {
            $file = $exception->getFile();
            $error = $exception->getMessage();
            $lineNumber = $exception->getLine();
            $response = [
                'chat_id' => '511057877',
                'text' => 'Error ' . $error . '  File ' . $file . '  LIne ' . $lineNumber
            ];

            return $this->sendTelegramMessage('sendMessage', $response);
        }
        Log::info('ok');

        $response = [
            'chat_id' => '511057877',
            'text' => 'You said: ' . $message . '  Error5 ' . $error
        ];

        $this->sendTelegramMessage('sendMessage', $response);
    }

    /**
     * Display a listing of the resource.
     * @return void
     */
    public final function parse(Request $request): void
    {
        $this->historyParser->parse($request);
    }

    private function sendTelegramMessage($method, $data)
    {
        $telegramToken = '6271480020:AAH0akUc6QWlroMlHS3kh2lu9J6Oyb975dw';
        $url = 'https://api.telegram.org/bot' . $telegramToken . '/' . $method;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}
