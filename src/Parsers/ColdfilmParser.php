<?php

namespace App\Parsers;

use App\CompilerPass\CoreInterfaces\RegistryItemInterface;
use App\Services\HttpClient;
use App\Structs\VideoParsingResultStruct;

class ColdfilmParser implements RegistryItemInterface
{
    const URL = 'http://coldfilm.ws/news/?page%d';

    /**
     * @var HttpClient
     */
    private $httpClient;

    public function __construct(HttpClient $client)
    {
        $this->httpClient = $client;
    }

    public function getName(): string
    {
        return 'coldfilm';
    }

    /**
     * @return void
     */
    public function afterRegistry()
    {

    }

    public function parse(\DateTime $from): VideoParsingResultStruct
    {
        $result = new VideoParsingResultStruct();
        $this->parseCatalog($result, $from);

        return $result;
    }

    private function parseCatalog(VideoParsingResultStruct $struct, \DateTime $from)
    {
        $url = sprintf(self::URL, 1);
        var_dump($this->httpClient->get($url));
    }

    private function parseItem()
    {

    }
}