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
use Rise\Bundle\CartBundle\Form\WishForm;
use Rise\Bundle\CartBundle\Model\Wish;

class WishAdmin extends AbstractModelAdmin
{
    public $permissions = [
        'create' => false,
        'update' => false,
        'info' => true,
        'remove' => false,
    ];

    /**
     * @return string model class name
     */
    public function getModelClass()
    {
        return Wish::class;
    }

    public function getFormType()
    {
        return WishForm::class;
    }
}
