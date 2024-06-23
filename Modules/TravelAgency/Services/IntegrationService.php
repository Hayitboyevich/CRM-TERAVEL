<?php

namespace Modules\TravelAgency\Services;

use ReflectionException;

class IntegrationService implements HelperInterface
{

    public function __construct(public IntegrationFactory $factory)
    {

    }

    /**
     * @throws ReflectionException
     */
    public function getFromCities(): array
    {
        $items = $this->factory->newHandler('getFromTowns');
        $data = [];
        foreach ($items as $item) {
            $data['name'] = $item["name"];
            $data['name'] = $item["name"];

        }
    }
}