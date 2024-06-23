<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MetaController extends Controller
{
    public function handleFacebookWebhook(Request $request)
    {
        $token = 'asadbek';
        $received_updates = [];
        Log::info(now());
        Log::info($request->all());
        return response($request->hub_challenge, 200);

        if (!$request->input('hub.mode')) {
            return response([]);
        } else {
        }
        if ($request->input('hub.mode') === 'subscribe' && $request->input('hub.verify_token') === $token) {
            return response($request->input('hub.challenge'), 200);
        } else {
            return response('Invalid token', 400);
        }
    }


}
