<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\OrderBundle\Model;

interface OrderInterface
{
    /**
     * @return int|float|string
     */
    public function getPrice();

    /**
     * @return int|float|string
     */
    public function getPriceTotal();

    /**
     * @return string|int
     */
    public function getId();
}
