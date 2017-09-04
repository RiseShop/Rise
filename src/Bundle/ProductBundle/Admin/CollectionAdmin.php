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
use Rise\Bundle\ProductBundle\Form\CollectionFormType;
use Rise\Bundle\ProductBundle\Model\Collection;

class CollectionAdmin extends AbstractModelAdmin
{
    public $columns = ['name'];

    /**
     * @return string model class name
     */
    public function getModelClass()
    {
        return Collection::class;
    }

    public function getFormType()
    {
        return CollectionFormType::class;
    }
}
