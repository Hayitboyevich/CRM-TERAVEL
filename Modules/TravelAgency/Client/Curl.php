<?php

namespace Modules\TravelAgency\Client;

class Curl implements ClientInterface
{
    public $curl;
    public string $url;
    public array $headers;
    private array $result;

    public final function init(string $url): Curl
    {
        $this->url = $url;
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_HEADER, 1);

        return $this;
    }

    public final function method(string $method, array $postParameter = null): Curl
    {
        if (strtolower($method) == 'get') {
            $parameters = http_build_query($postParameter);
            curl_setopt($this->curl, CURLOPT_URL, $this->url . '?' . $parameters);
        } else if (strtolower($method) == 'post') {
            curl_setopt($this->curl, CURLOPT_POST, true);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $postParameter);
        }

        return $this;
    }

    public final function userAgentSwitcher(): Curl
    {
        $user_agents = json_decode(file_get_contents(public_path('user-agents.json')), true);

        $user_agent = $user_agents[array_rand($user_agents)]['ua'];

        curl_setopt($this->curl, CURLOPT_USERAGENT, $user_agent);

        return $this;
    }

    public final function execute(): Curl
    {
        $this->result['content'] = curl_exec($this->curl);

        $header_size = curl_getinfo($this->curl, CURLINFO_HEADER_SIZE);
        $headers = substr($this->result['content'], 0, $header_size);
        $this->result['content'] = substr($this->result['content'], $header_size);
        $this->result['responseHeaders'] = $this->getHeaders($headers);
        $contentType = curl_getinfo($this->curl, CURLINFO_CONTENT_TYPE);
        $this->result['contentType'] = $contentType;
        $this->result['redirect_url'] = curl_getinfo($this->curl, CURLINFO_REDIRECT_URL);

        curl_close($this->curl);

        return $this;
    }

    public final function getHeaders($respHeaders): array
    {
        $headers = array();
        $headerText = substr($respHeaders, 0, strpos($respHeaders, "\r\n\r\n"));
        foreach (explode("\r\n", $headerText) as $i => $line) {
            if ($i === 0) {
                $headers['http_code'] = $line;
            } else {
                list ($key, $value) = explode(': ', $line);
                $headers[$key] = $value;
            }
        }
        return $headers;
    }

    public final function setHeaders(array $headers): Curl
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
        return $this;
    }

    public final function getBody()
    {
        return $this->result['content'];
    }

    public final function getContentType()
    {
        return $this->result['contentType'];
    }

    public function getRedirectUrl()
    {
        return $this->result['redirect_url'];
    }

    public final function getHeader($name = '*')
    {
        if ($name == '*') {
            return $this->result['responseHeaders'];
        }

        return $this->result['responseHeaders'][$name];
    }

}
