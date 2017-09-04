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
use Rise\Bundle\GeoBundle\Form\CityForm;
use Rise\Bundle\GeoBundle\Model\City;

class CityAdmin extends AbstractModelAdmin
{
    /**
     * @return string model class name
     */
    public function getModelClass()
    {
        return City::class;
    }

    public function getFormType()
    {
        return CityForm::class;
    }
}
