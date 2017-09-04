<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\DeliveryBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DeliveryPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('rise.bundle.delivery.factory');
        if (!$definition) {
            return;
        }

        $methodDefinitions = $container->findTaggedServiceIds('delivery.service');
        foreach ($methodDefinitions as $id => $raw) {
            $definition->addMethodCall('registerService', [
                $id,
                new Reference($id),
            ]);
        }
    }
}
