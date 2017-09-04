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

interface PurchaseParametersInterface
{
    /**
     * @return OrderInterface
     */
    public function getOrder();

    /**
     * @return CustomerInterface
     */
    public function getCustomer();

    /**
     * @return AttemptInterface
     */
    public function getAttempt();
}
