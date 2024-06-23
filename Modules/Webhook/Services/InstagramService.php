<?php

namespace Modules\WebHook\Services;

use Modules\WebHook\Repositories\InstagramRepository;

class InstagramService implements WebhookInterface
{
    const INSTAGRAM = 'instagram';

    public function setData($data)
    {
        $repository = new InstagramRepository($data);
        $repository->create();
//        event(new WebHookEvent($dto));
    }

    public static function getName(): string
    {
        return self::INSTAGRAM;
    }
}
