<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Delivery\Service;

use Rise\Component\Delivery\DimensionsInterface;
use Rise\Component\Delivery\LocationInterface;
use Rise\Component\Delivery\ParametersInterface;
use Rise\Component\Delivery\ResultInterface;

interface DeliveryServiceInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param LocationInterface   $from
     * @param LocationInterface   $to
     * @param DimensionsInterface $dimensions
     * @param ParametersInterface $parameters
     *
     * @return null|ResultInterface
     */
    public function getPrice(LocationInterface $from, LocationInterface $to, DimensionsInterface $dimensions, ParametersInterface $parameters = null);

    /**
     * @param string $country
     * @param string $region
     * @param string $city
     *
     * @return bool
     */
    public function support($country, $region, $city);
}
