<?php

namespace App\CompilerPass\CoreInterfaces;

interface RegistryInterface
{
    public function register(RegistryItemInterface $item);
}