<?php

namespace Modules\WebHook\Services;

use Modules\WebHook\DTO\SocialDto;

interface WebhookInterface
{
    public function setData(SocialDto $data);

    public static function getName():string;
}
