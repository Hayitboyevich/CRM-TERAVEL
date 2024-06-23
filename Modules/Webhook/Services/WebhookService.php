<?php

namespace Modules\WebHook\Services;

class WebhookService
{
    public static function fabric(string $name): WebhookInterface
    {
        /**
         * @var $class WebhookInterface
         */
        foreach (config('services.webhook') as $key=>$class) {
            if ($key == $name) {
                return new $class;
            }
        }
        throw new \Exception('Provider not found');
    }
}
