<?php

namespace App\Parsers\Sites;

use App\CompilerPass\CoreInterfaces\RegistryInterface;
use App\CompilerPass\CoreInterfaces\RegistryItemInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

class SiteParserRegistry implements RegistryInterface
{
    /**
     * @var SiteParserInterface[]
     */
    private $services = [];

    public function register(RegistryItemInterface $item)
    {
        if (!$item instanceof SiteParserInterface) {
            throw new InvalidArgumentException('Required implement SiteParserInterface');
        }
        $this->services[$item->getName()] = $item;

        $item->afterRegistry();
    }

    public function getByName(string $name): ?SiteParserInterface
    {
        return $this->services[$name] ?? null;
    }

    /**
     * @return SiteParserInterface[]
     */
    public function getAll(): array
    {
        return $this->services;
    }
}