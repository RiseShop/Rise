<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Delivery;

interface LocationInterface
{
    /**
     * @return int|string
     */
    public function getCountry();

    /**
     * @return int|string
     */
    public function getRegion();

    /**
     * @return int|string
     */
    public function getCity();

    /**
     * @return int|string
     */
    public function getZipCode();

    /**
     * @return int|string
     */
    public function getAddress();
}
