<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Delivery;

use Rise\Component\Delivery\Service\DeliveryServiceInterface;

class Factory
{
    /**
     * @var DeliveryServiceInterface[]
     */
    protected $services = [];

    /**
     * @param $code
     * @param DeliveryServiceInterface $service
     */
    public function registerService($code, DeliveryServiceInterface $service)
    {
        $this->services[$code] = $service;
    }

    /**
     * @param $code
     *
     * @return DeliveryServiceInterface|null
     */
    public function getService($code)
    {
        if (isset($this->services[$code])) {
            return $this->services[$code];
        }

        return null;
    }

    public function getServices()
    {
        return $this->services;
    }
}
