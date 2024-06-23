<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AlbatoRequest;
use Exception;
use Illuminate\Support\Facades\Http;
use Modules\TelegramHelper\Services\HistoryParser;

class AlbatoIntegrationController extends Controller
{
    /**
     * @throws Exception
     */
    public function webhook(AlbatoRequest $request)
    {
        $response = implode(', ', $request->all());

        $data = $request->validated();
        $historyParser = new HistoryParser();
        $historyParser->store($data['mobile'], $data['name'], $data['country'], $data['quantity']);

        $response = Http::post("https://api.telegram.org/bot5874061502:AAGGIBkD9u3LiUXaPVtZBTFoYCCsltxbMeM/sendMessage", [
            'chat_id' => '511057877',
            'text' => $response,
            'parse_mode' => 'HTML',
        ]);
        return response()->json(["ok" => "true"]);
    }
}