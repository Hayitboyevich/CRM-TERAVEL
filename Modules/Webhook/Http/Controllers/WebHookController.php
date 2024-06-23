<?php

namespace Modules\Webhook\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\WebHook\DTO\InstagramDto;
use Modules\WebHook\Services\WebhookService;

class WebHookController extends Controller
{
    const INSTAGRAM = 'instagram';
    /**
     * Display a listing of the resource.
     */
    public function handle($data)
    {
        $service = WebhookService::fabric($data->name);
        $service->setData($data);
    }

    public function register(Request $request)
    {

        if ($request->get('hub_mode') === 'subscribe' && $request->get('hub_verify_token') === self::INSTAGRAM) {
            return $request->get('hub_challenge');
        } else {
            echo 'invalid token';
        }
    }

    public function process(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $token = $request->query('token');
        $instagramDto = new InstagramDto();
        $instagramDto->setMeta($data)
            ->setToken($token);

        $this->handle($instagramDto);
    }
}
