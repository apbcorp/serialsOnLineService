<?php

namespace App\Parsers\VideoResources;

use App\Structs\Serial\PlaylistStruct;

class VioToParser extends AbstractVideoResourceParser
{
    public function getName(): string
    {
        return 'vio.to';
    }

    public function parse(string $url): ?PlaylistStruct
    {
        return null;
    }
}