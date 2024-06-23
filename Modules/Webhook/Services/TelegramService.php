<?php

namespace Modules\WebHook\Services;

use Modules\WebHook\App\DTO\LeadDto;
use Modules\WebHook\App\Events\WebHookEvent;

class TelegramService implements WebhookInterface
{
    const TELEGRAM = 'telegram';

    public function setData($data)
    {
        info($data->name);
//        event(new WebhookEvent($dto));
    }

    public static function getName(): string
    {
        return self::TELEGRAM;
    }
}
