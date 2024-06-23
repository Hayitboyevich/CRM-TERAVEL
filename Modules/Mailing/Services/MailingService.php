<?php

namespace Modules\Mailing\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class MailingService
{
    public function send($phone, $message)
    {
//        $username = env('SMS_USERNAME');
//        $password = env('SMS_PASSWORD');
//        try {
//            $response = Http::asJson()->withBasicAuth($username, $password)
//                ->post('https://sms.olcha.uz/api/send', [
//                    "messages" => [
//                        [
//                            "recipient" => '998' . $phone,
//                            "sms" => [
//                                "content" => [
//                                    "text" => $message
//                                ]
//                            ]
//                        ]
//                    ]
//                ]);
//
//            $response = $response->body();
//
//        } catch (Exception $exception) {
//            dd($exception);
//        }
//
//        return json_decode($response, true);

    }
}
