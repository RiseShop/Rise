<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\OrderBundle\Admin;

use Mindy\Bundle\AdminBundle\Admin\AbstractModelAdmin;
use Rise\Bundle\OrderBundle\Form\CustomerForm;
use Rise\Bundle\OrderBundle\Model\Customer;

class CustomerAdmin extends AbstractModelAdmin
{
    public $columns = ['id', 'last_name', 'first_name', 'middle_name', 'phone', 'email'];

    public $permissions = [
        'info' => false,
    ];

    /**
     * @return string model class name
     */
    public function getModelClass()
    {
        return Customer::class;
    }

    public function getFormType()
    {
        return CustomerForm::class;
    }
}
