<?php

namespace Modules\WebHook\DTO;

class InstagramDto implements SocialDto
{
    public string $name;

    public array $meta = [];

    public string $token;

    public function setMeta($data): self
    {
       $this->name = $data['object'];
       $this->meta = $data['entry'][0];
        return $this;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }
}
