<?php

namespace App\Parsers\VideoResources;

use App\CompilerPass\CoreInterfaces\RegistryItemInterface;
use App\Structs\Serial\PlaylistStruct;

interface VideoResourceParserInterface extends RegistryItemInterface
{
    public function isMyService(string $url): bool;

    public function parse(string $url): PlaylistStruct;
}