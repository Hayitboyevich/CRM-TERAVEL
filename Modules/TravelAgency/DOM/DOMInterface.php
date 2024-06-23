<?php

namespace Modules\TravelAgency\DOM;

interface DOMInterface
{
    public function find(string $selector);

    public function nthChild(int $position);

    public function text();

    public function innerText();

    public function attr(string $attribute);

    public function count();

    public function each(callable $callback);
}
