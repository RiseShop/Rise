<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\OrderBundle\AdminHandler;

use Rise\Bundle\OrderBundle\Model\Order;

class OrderHandler
{
    public function count()
    {
        $qs = Order::objects()->filter(['status_id' => Order::STATUS_NEW]);

        return $qs->count();
    }
}
