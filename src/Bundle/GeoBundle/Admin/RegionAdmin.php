<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\GeoBundle\Admin;

use Mindy\Bundle\AdminBundle\Admin\AbstractModelAdmin;
use Rise\Bundle\GeoBundle\Form\RegionForm;
use Rise\Bundle\GeoBundle\Model\Region;

class RegionAdmin extends AbstractModelAdmin
{
    /**
     * @return string model class name
     */
    public function getModelClass()
    {
        return Region::class;
    }

    public function getFormType()
    {
        return RegionForm::class;
    }
}
