<?php

namespace Modules\TravelAgency\DOM;


use Symfony\Component\DomCrawler\Crawler;

class HtmlDomAdapter implements DOMInterface
{
    protected Crawler $crawler;

    protected Crawler $node;

    protected string $html;

    public function __construct(string $html)
    {
        $this->crawler = new Crawler($html);
    }

    public function find(string $selector)
    {
        $this->node = $this->crawler->filter($selector);

        return $this;
    }

    public function text()
    {
        return $this->node->text();
    }

    public function nthChild(int $position)
    {
        $this->node = $this->node->eq($position);

        return $this;
    }

    public function innerText(): string
    {
        return $this->node->innerText();
    }

    public function attr(string $attribute): string
    {
        return $this->node->attr($attribute);
    }

    public function count(): int
    {
        return $this->node->count();
    }

    public function each(callable $callback): array
    {
        return $this->node->each($callback);
    }
}
