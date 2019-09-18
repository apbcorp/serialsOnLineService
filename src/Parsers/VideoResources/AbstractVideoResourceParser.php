<?php

namespace App\Parsers\VideoResources;

use App\Services\HttpClient;

abstract class AbstractVideoResourceParser implements VideoResourceParserInterface
{
    /**
     * @var HttpClient
     */
    protected $client;

    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    public function afterRegistry()
    {

    }

    public function isMyService(string $url): bool
    {
        return strpos($url, sprintf('/%s/', $this->getName())) === false
            ? false
            : true;
    }
}