<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Cart;

interface PositionInterface
{
    /**
     * @param CartStorageInterface $storage
     */
    public function setCartStorage(CartStorageInterface $storage);

    /**
     * Return total price: product.price * quantity.
     *
     * @return float
     */
    public function getPrice();

    /**
     * @return int
     */
    public function getQuantity();

    /**
     * @return ProductInterface
     */
    public function getProduct();

    /**
     * @return string
     */
    public function generateUniqueId();

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity);
}
