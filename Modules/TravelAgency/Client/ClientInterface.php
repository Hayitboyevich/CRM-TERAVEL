<?php

namespace Modules\TravelAgency\Client;

interface ClientInterface
{
    public function init(string $url);

    public function setHeaders(array $headers);

    public function getHeaders($respHeaders);

    public function method(string $method, array $postParameter = null);

    public function userAgentSwitcher();

    public function execute();

    public function getBody();

    public function getContentType();

    public function getHeader($name = '*');
}
