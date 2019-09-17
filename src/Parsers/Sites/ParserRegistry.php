<?php

namespace App\Parsers\Sites;

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

    public function getByName(string $name): ?RegistryItemInterface
    {
        return $this->services[$name] ?? null;
    }

    /**
     * @return RegistryItemInterface[]
     */
    public function getAll(): array
    {
        return $this->services;
    }
}