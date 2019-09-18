<?php

namespace App\Parsers\VideoResources;

use App\CompilerPass\CoreInterfaces\RegistryInterface;
use App\CompilerPass\CoreInterfaces\RegistryItemInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

class VideoResourceParserRegistry implements RegistryInterface
{
    /**
     * @var VideoResourceParserInterface[]
     */
    private $services = [];

    public function register(RegistryItemInterface $item)
    {
        if (!$item instanceof VideoResourceParserInterface) {
            throw new InvalidArgumentException('Required implement VideoResourceParserInterface');
        }

        $this->services[$item->getName()] = $item;

        $item->afterRegistry();
    }

    public function getByUrl(string $url): ?VideoResourceParserInterface
    {
        foreach ($this->services as $service) {
            if ($service->isMyService($url)) {
                return $service;
            }
        }

        return null;
    }

    /**
     * @return RegistryItemInterface[]
     */
    public function getAll(): array
    {
        return $this->services;
    }
}