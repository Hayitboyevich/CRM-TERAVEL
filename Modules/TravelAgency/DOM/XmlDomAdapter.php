<?php

namespace Modules\TravelAgency\DOM;

use SimpleXMLElement;

class XmlDomAdapter implements DOMInterface
{
    public $node;

    public function __construct(string $page)
    {
        $this->node = simplexml_load_string($page);
    }

    public function find(string $selector): XmlDomAdapter
    {
        $selectors = explode(' ', $selector);

        $array = array();

        foreach ($selectors as $key => $selector) {
            if (array_key_first($selectors) == $key) {
                foreach ($this->node->{$selector} as $node) {
                    $array[] = $node;
                }
            } else {
                $array = $this->array_foreach($array, $selector);
            }
        }

        $this->node = $array;

        return $this;

    }

    public function array_foreach($array, $selector): array
    {
        $output = array();
        foreach ($array as $item) {
            $output[] = $item->{$selector};
        }

        return $output;
    }

    public function nthChild(int $position): XmlDomAdapter
    {
        $this->node = $this->node[$position];

        return $this;
    }

    public function text(): string
    {
        if (is_array($this->node)) {
            $this->node = $this->node[0];
        }
        return dom_import_simplexml($this->node)->textContent;
    }

    public function innerText(): string
    {
        return $this->node;
    }

    public function attr(string $attribute): string
    {
        return $this->node[$attribute];
    }

    public function count(): int
    {
        return $this->node->count();
    }

    public function each(callable $callback): array
    {
        $data = [];
        foreach ($this->node as $node) {
            $data[] = $callback($this->createSubNode($node));
        }

        return $data;
    }

    private function createSubNode(SimpleXMLElement $node): XmlDomAdapter
    {
        $this->node = $node;

        return $this;
    }
}
