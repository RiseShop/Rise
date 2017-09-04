<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\OrderBundle\EventListener;

use Rise\Bundle\OrderBundle\Model\Order;
use Symfony\Component\EventDispatcher\Event;

class OrderCreateEvent extends Event
{
    const NAME = 'order.create';

    /**
     * @var Order
     */
    protected $order;

    /**
     * OrderCreateEvent constructor.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }
}
