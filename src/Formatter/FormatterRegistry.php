<?php

namespace App\Formatter;

use App\CompilerPass\CoreInterfaces\RegistryInterface;
use App\CompilerPass\CoreInterfaces\RegistryItemInterface;

class FormatterRegistry implements RegistryInterface
{
    /**
     * @var array
     */
    private $services = [];

    public function register(RegistryItemInterface $item)
    {
        if (!$item instanceof FormatterInterface) {
            throw new \InvalidArgumentException('Required implement FormatterInterface');
        }

        $this->services[$item->getName()] = $item;

        $item->afterRegistry();
    }

    public function getByName(string $name): ?FormatterInterface
    {
        return isset($this->services[$name]) ? $this->services[$name] : null;
    }
}