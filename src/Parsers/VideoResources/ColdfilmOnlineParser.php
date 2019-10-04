<?php

namespace App\Parsers\VideoResources;

use App\Helper\M3U8Helper;
use App\Structs\Serial\PlaylistStruct;

class ColdfilmOnlineParser extends AbstractVideoResourceParser
{
    public function getName(): string
    {
        return 'coldfilm.online';
    }

    public function parse(string $url): ?PlaylistStruct
    {
        $html = $this->client->get($url);
        if (!preg_match('/data-config=\'(.*)\'/', $html, $matches)) {
            return null;
        }

        $config = json_decode($matches[1], true);
        $result = new PlaylistStruct();
        $result->setM3U8Items(M3U8Helper::parse($config['hls'], $this->client->get($config['hls']), $this->getName()));

        return $result;
    }
}