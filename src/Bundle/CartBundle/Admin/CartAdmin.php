<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\CartBundle\Admin;

use Mindy\Bundle\AdminBundle\Admin\AbstractModelAdmin;
use Rise\Bundle\CartBundle\Form\CartForm;
use Rise\Bundle\CartBundle\Model\Cart;

class CartAdmin extends AbstractModelAdmin
{
    public $columns = ['user', 'session_id', 'created_at'];

    public $defaultOrder = ['-id'];

    public $permissions = [
        'update' => false,
        'create' => false,
        'remove' => false
    ];

    /**
     * @return string model class name
     */
    public function getModelClass()
    {
        return Cart::class;
    }

    public function getFormType()
    {
        return CartForm::class;
    }
}
