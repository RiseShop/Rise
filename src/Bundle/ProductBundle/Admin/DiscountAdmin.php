<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\ProductBundle\Admin;

use Mindy\Bundle\AdminBundle\Admin\AbstractModelAdmin;
use Rise\Bundle\ProductBundle\Form\DiscountFormType;
use Rise\Bundle\ProductBundle\Model\Discount;

class DiscountAdmin extends AbstractModelAdmin
{
    public $columns = ['name', 'description', 'discount'];

    /**
     * @return string model class name
     */
    public function getModelClass()
    {
        return Discount::class;
    }

    public function getFormType()
    {
        return DiscountFormType::class;
    }
}
