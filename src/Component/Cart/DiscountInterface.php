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

interface DiscountInterface
{
    public function supports(CartInterface $cart, UserInterface $user);

    public function apply($price);
}
