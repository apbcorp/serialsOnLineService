<?php

namespace App\Formatter;

use App\CompilerPass\CoreInterfaces\RegistryItemInterface;
use App\Entity\Serial;

class SerialListFormatter implements RegistryItemInterface, FormatterInterface
{
    const NAME = 'serialList';

    public function getName(): string
    {
        return self::NAME;
    }

    public function afterRegistry()
    {

    }

    /**
     * @param Serial[] $data
     *
     * @return array
     */
    public function format($data): array
    {
        $result = [];

        foreach ($data as $serial) {
            $result[] = [
                'id' => $serial->getId(),
                'name' => $serial->getName()
            ];
        }

        return $result;
    }
}