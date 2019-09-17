<?php

namespace App\CompilerPass\CoreInterfaces;

use App\Structs\VideoParsingResultStruct;

interface RegistryItemInterface
{
    public function afterRegistry();

    public function getName(): string;

    public function parse(\DateTime $from): VideoParsingResultStruct;
}