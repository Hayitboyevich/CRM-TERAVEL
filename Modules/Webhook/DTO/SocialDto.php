<?php

namespace Modules\WebHook\DTO;

interface SocialDto
{
    public function setMeta($data);
    public function setToken(string $token);
}
