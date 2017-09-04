<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Cart;

use Symfony\Component\Security\Core\User\UserInterface;

interface CartInterface
{
    /**
     * @return PositionInterface[]
     */
    public function getPositions();

    /**
     * @return int
     */
    public function getQuantity();

    /**
     * @return float
     */
    public function getPrice();

    /**
     * @param UserInterface $user
     *
     * @return float
     */
    public function getTotalPrice(UserInterface $user);

    /**
     * @param string            $key
     * @param PositionInterface $position
     */
    public function addPosition($key, PositionInterface $position);

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
     * @param CartStorageInterface $storage
     */
    public function setStorage(CartStorageInterface $storage);

    /**
     * @param string $key
     *
     * @return PositionInterface
     */
    public function getPosition($key);

    /**
     * Remove all positions from cart
     */
    public function clear();
}
