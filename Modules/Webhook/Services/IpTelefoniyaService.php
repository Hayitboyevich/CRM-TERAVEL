<?php

namespace Modules\WebHook\Services;

use Modules\WebHook\App\DTO\LeadDto;
use Modules\WebHook\App\Events\WebHookEvent;

class IpTelefoniyaService implements WebhookInterface
{
    const IPTELEFONIYA = 'ip-telefoniya';

    public function setData(LeadDto $dto)
    {
        event(new WebhookEvent($dto));
    }


    public static function getName(): string
    {
        return self::IPTELEFONIYA;
    }


}
