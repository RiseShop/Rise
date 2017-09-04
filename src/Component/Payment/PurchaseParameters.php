<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Payment;

use Rise\Bundle\OrderBundle\Model\CustomerInterface;
use Rise\Bundle\OrderBundle\Model\OrderInterface;
use Rise\Bundle\PaymentBundle\Model\AttemptInterface;

class PurchaseParameters implements PurchaseParametersInterface
{
    /**
     * @var OrderInterface
     */
    protected $order;
    /**
     * @var CustomerInterface
     */
    protected $customer;
    /**
     * @var AttemptInterface
     */
    protected $attempt;

    /**
     * @return OrderInterface
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param OrderInterface $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return CustomerInterface
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param CustomerInterface $customer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return AttemptInterface
     */
    public function getAttempt()
    {
        return $this->attempt;
    }

    /**
     * @param AttemptInterface $attempt
     */
    public function setAttempt($attempt)
    {
        $this->attempt = $attempt;
    }
}
