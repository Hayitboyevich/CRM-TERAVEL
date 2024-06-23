<?php

namespace Modules\QuiQoe\DTO;

class HotelsDTO
{
    /**
     * @var HotelsDTO[]
     */
    protected ?array $hotels;

    public function setData(array $data): void
    {
        $this->hotels[] = $data;
    }

    public function getHotels()
    {
        return $this->hotels;
    }

}
