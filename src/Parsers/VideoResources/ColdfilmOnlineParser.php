<?php

namespace App\Parsers\VideoResources;

use App\Structs\Serial\PlaylistStruct;

class ColdfilmOnlineParser extends AbstractVideoResourceParser
{
    public function getName(): string
    {
        return 'coldfilm.online';
    }

    public function parse(string $url): PlaylistStruct
    {
        $result = new PlaylistStruct();

        return $result;
    }
}