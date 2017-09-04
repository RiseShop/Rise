<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\PaymentBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class PaymentPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('rise.bundle.payment.factory')) {
            return;
        }

        $definition = $container->getDefinition('rise.bundle.payment.factory');

        $methodDefinitions = $container->findTaggedServiceIds('payment.gateway');

        $methods = [];
        foreach ($methodDefinitions as $id => $raw) {
            $methods[$id] = new Reference($id);
        }

        $definition->addMethodCall('setGateways', [$methods]);
    }
}
