<?php

namespace Modules\PassportReader;

use Illuminate\Support\Facades\Http;

class Reader
{
    public static function scan(string $back, string $front): array
    {
        $response = Http::asJson()->post('http://92.204.253.20/api/v1/ocr/verification',
            [
                'selfie' => 'https://olcha.uz/image/original/4DWWmrm3jP7wEiikyvOhPSHQ125180.jpg',
                'front' => 'https://olcha.uz/image/original/nsLnXB8ozegX4LtqTnQtdSb0125180.jpg',
                'back' => 'https://olcha.uz/image/original/EvnAAUC7AyDNf5cabvaVofgy125180.jpg'
            ]
        );
        $status = $response->status();
        if ($status >= 200 && $status < 300) {
            $result = $response->body();
            return json_decode($result, true);
        }
        return [
            'status' => false
        ];
    }

}
