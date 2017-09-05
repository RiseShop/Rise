<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\CartBundle\Library;

use Mindy\Template\Library;
use Rise\Component\Cart\CartInterface;

class CartLibrary extends Library
{
    /**
     * @var CartInterface
     */
    protected $cart;

    /**
     * CartLibrary constructor.
     *
     * @param CartInterface $cart
     */
    public function __construct(CartInterface $cart)
    {
        $this->cart = $cart;
    }

    /**
     * @return array
     */
    public function getHelpers()
    {
        return [
            'cart' => function () {
                return $this->cart;
            },
            'unserialize' => function ($value) {
                return unserialize($value);
            },
        ];
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return [];
    }
}
