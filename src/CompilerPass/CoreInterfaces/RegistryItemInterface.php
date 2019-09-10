<?php

namespace App\CompilerPass\CoreInterfaces;

interface RegistryItemInterface
{
    public function afterRegistry();

    public function getName(): string;
}