<?php

namespace App\Parsers\Sites;

use App\CompilerPass\CoreInterfaces\RegistryItemInterface;
use App\Structs\Serial\VideoParsingResultStruct;

interface SiteParserInterface extends RegistryItemInterface
{
    public function parse(\DateTime $from): VideoParsingResultStruct;
}