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
use Rise\Bundle\PaymentBundle\Model\Attempt;

class AttemptAdmin extends AbstractModelAdmin
{
    public $columns = ['payment', 'order', 'is_success', 'is_fail', 'is_cancel', 'fail_error', 'created_at'];

    public $permissions = [
        'create' => false,
        'update' => false,
        'info' => true,
        'remove' => false,
    ];

    public function getFormType()
    {
        return null;
    }

    public function getModelClass()
    {
        return Attempt::class;
    }
}
