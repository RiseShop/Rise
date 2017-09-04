<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\PaymentBundle\Admin;

use Mindy\Bundle\AdminBundle\Admin\AbstractModelAdmin;
use Rise\Bundle\PaymentBundle\Form\PaymentForm;
use Rise\Bundle\PaymentBundle\Model\Payment;

class PaymentAdmin extends AbstractModelAdmin
{
    public $sorting = 'position';

    public $columns = ['name', 'gateway', 'is_enabled'];

    /**
     * @return string model class name
     */
    public function getModelClass()
    {
        return Payment::class;
    }

    public function getFormType()
    {
        return PaymentForm::class;
    }
}
