<?php

namespace App\Parsers;

use App\CompilerPass\CoreInterfaces\RegistryInterface;
use App\CompilerPass\CoreInterfaces\RegistryItemInterface;

class ParserRegistry implements RegistryInterface
{
    /**
     * @var RegistryItemInterface[]
     */
    private $services = [];

    public function register(RegistryItemInterface $item)
    {
        $this->services[$item->getName()] = $item;

        $item->afterRegistry();
    }
}