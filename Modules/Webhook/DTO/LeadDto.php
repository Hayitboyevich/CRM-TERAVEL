<?php


namespace Modules\WebHook\DTO;

class LeadDto
{
    public array $meta= [];

    public function setMeta($request): self
    {
        $this->meta = array_merge($this->meta, $request->input('params'));
        $this->meta['type'] = $request->input('name');
        return $this;
    }

}
