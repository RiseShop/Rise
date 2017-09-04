<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Delivery;

use Rise\Component\Delivery\Method\DeliveryMethodInterface;

class DeliveryFactory
{
    /**
     * @var DeliveryMethodInterface[]
     */
    protected $methods = [];

    /**
     * @param DeliveryMethodInterface[] $methods
     */
    public function setMethods(array $methods = [])
    {
        $this->methods = $methods;
    }

    /**
     * @return DeliveryMethodInterface[]
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @param string $type
     *
     * @return DeliveryMethodInterface|null
     */
    public function getMethod($type)
    {
        foreach ($this->getMethods() as $method) {
            if ($method->getType() == $type) {
                return $method;
            }
        }
    }
}
