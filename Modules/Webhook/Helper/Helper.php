<?php

namespace Modules\WebHook\Helper;

use function Laravel\Prompts\text;

class Helper
{
    public static function is($text, $verify)
    {
        if (str_contains($text, $verify)) {
            return true;
        }
        return false;
    }

    public static function is_not($text, $verify)
    {
        return $text != $verify;
    }

    public static function regexp($text, $verify)
    {
        if (!preg_match($verify, $text)) {
            return false;
        }
        return true;
    }
}
