<?php

namespace App\Services;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HttpClient
{
    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function get(string $url, array $params = [])
    {
        $url = $this->buildUrl($url, $params);

        try {
            $result = $this->getClient()->request(Request::METHOD_GET, $url);
        } catch (TransportExceptionInterface $e) {
            $this->logger->critical('Get Request Exception', ['message' => $e->getMessage()]);

            return '';
        }

        return $result->getContent();
    }

    private function buildUrl(string $url, array $params): string
    {
        if (!$params) {
            return $url;
        }

        $paramsAsString = http_build_query($params);

        return strpos($url, '?') === false
            ? $url . '?' . $paramsAsString
            : $url . '&' . $paramsAsString;
    }

    private function getClient(): HttpClientInterface
    {
        if (!$this->client) {
            $this->client = \Symfony\Component\HttpClient\HttpClient::create();
        }

        return $this->client;
    }
}