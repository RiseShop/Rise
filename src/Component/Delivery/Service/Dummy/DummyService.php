<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Delivery\Service\Dummy;

use Rise\Component\Delivery\DimensionsInterface;
use Rise\Component\Delivery\LocationInterface;
use Rise\Component\Delivery\ParametersInterface;
use Rise\Component\Delivery\ResultInterface;
use Rise\Component\Delivery\Service\DeliveryServiceInterface;

class DummyService implements DeliveryServiceInterface
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'dummy';
    }

    /**
     * @param LocationInterface   $from
     * @param LocationInterface   $to
     * @param DimensionsInterface $dimensions
     * @param ParametersInterface $parameters
     *
     * @return null|ResultInterface
     */
    public function getPrice(LocationInterface $from, LocationInterface $to, DimensionsInterface $dimensions, ParametersInterface $parameters = null)
    {
        return rand(100, 999);
    }

    /**
     * @param string $country
     * @param string $region
     * @param string $city
     *
     * @return bool
     */
    public function support($country, $region, $city)
    {
        return true;
    }
}
