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
use Rise\Bundle\ProductBundle\Form\Admin\ValueForm;
use Rise\Bundle\ProductBundle\Model\Value;

class ValueAdmin extends AbstractModelAdmin
{
    public function getFormType()
    {
        return ValueForm::class;
    }

    public function getModelClass()
    {
        return Value::class;
    }
}
