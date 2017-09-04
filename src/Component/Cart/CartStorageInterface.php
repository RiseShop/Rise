<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Cart;

interface CartStorageInterface
{
    /**
     * @return array|PositionInterface[]
     */
    public function getPositions();

    /**
     * @param string $key
     */
    public function removePosition($key);

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasPosition($key);

    /**
     * @param $key
     *
     * @return PositionInterface
     */
    public function getPosition($key);

    /**
     * Remove all positions from cart
     */
    public function clear();
}
