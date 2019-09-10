<?php

namespace App\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegistryCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $registries = $container->findTaggedServiceIds('system.registry');

        foreach ($registries as $registryName => $registryTags) {
            $registryDef = $container->getDefinition($registryName);
            $reflectedRegistry = new \ReflectionClass($registryName);

            if (!$reflectedRegistry->implementsInterface(RegistryInterface::class)) {
                throw new \RuntimeException('Registry can implement RegistryInterface');
            }

            foreach ($container->findTaggedServiceIds($registryName) as $id => $tags) {
                $registryDef->addMethodCall('register', [new Reference($id)]);
            }
        }
    }
}