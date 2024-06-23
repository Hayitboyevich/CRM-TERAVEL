<?php

namespace Modules\QuiQoe\DTO;

class HotelDTO
{
    /**
     * @param string|null $name
     * @param int|null $countryId
     * @param int|null $regionId
     * @param string|null $createdAt
     * @param string|null $updatedAt
     */

    public function __construct(
        public readonly ?int    $countryId,
        public readonly ?int    $regionId,
        public readonly ?string $name,
        public readonly ?string $createdAt,
        public readonly ?string $updatedAt,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'country_id' => $this->countryId,
            'region_id' => $this->regionId,
            'name' => $this->name,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
