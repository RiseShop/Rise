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
use Rise\Bundle\OrderBundle\Form\StatusForm;
use Rise\Bundle\OrderBundle\Model\Status;

class StatusAdmin extends AbstractModelAdmin
{
    /**
     * @return string model class name
     */
    public function getModelClass()
    {
        return Status::class;
    }

    public function getFormType()
    {
        return StatusForm::class;
    }
}
