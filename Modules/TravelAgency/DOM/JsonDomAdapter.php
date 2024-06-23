<?php

namespace Modules\TravelAgency\DOM;

class JsonDomAdapter implements DOMInterface
{
    protected $DOM;

    public function __construct(string $json)
    {
        $this->DOM = json_decode($json, true);
    }

    public function find(string $selector)
    {
        $selectors = explode(' ', $selector);

        foreach ($selectors as $selector) {
            $this->DOM = $this->DOM[$selector];
        }
        return $this;
    }

    public function nthChild(int $position)
    {
        // TODO: Implement nthChild() method.
    }

    public function text(): string
    {
        return $this->DOM;
    }

    public function innerText(): string
    {
        return $this->DOM;
    }

    public function attr(string $attribute): string
    {
        // TODO: Implement attr() method.
    }

    public function count(): int
    {
        // TODO: Implement count() method.
    }

    public function each(callable $callback): array
    {
        // TODO: Implement each() method.
    }
}
