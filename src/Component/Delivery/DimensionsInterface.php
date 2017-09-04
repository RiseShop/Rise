<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Delivery;

interface DimensionsInterface
{
    /**
     * @return float
     */
    public function getLength();

    /**
     * @return float
     */
    public function getHeight();

    /**
     * @return float
     */
    public function getWidth();

    /**
     * @return float
     */
    public function getWeight();

    /**
     * Объем
     *
     * @return float
     */
    public function getVolume();
}
