<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\DeliveryBundle\Admin;

use Mindy\Bundle\AdminBundle\Admin\AbstractModelAdmin;
use Rise\Bundle\DeliveryBundle\Form\DeliveryForm;
use Rise\Bundle\DeliveryBundle\Model\Delivery;

class DeliveryAdmin extends AbstractModelAdmin
{
    public $columns = ['name', 'service'];

    public function getFormType()
    {
        return DeliveryForm::class;
    }

    public function getModelClass()
    {
        return Delivery::class;
    }
}
