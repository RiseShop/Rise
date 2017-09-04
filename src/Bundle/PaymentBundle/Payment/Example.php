<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\PaymentBundle\Payment;

use Rise\Component\Payment\Gateway\Dummy\Dummy;

class Example extends Dummy
{
    public function getName()
    {
        return 'Тестовый способ оплаты';
    }
}
