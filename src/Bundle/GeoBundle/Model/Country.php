<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\GeoBundle\Model;

use Mindy\Orm\Fields\CharField;
use Mindy\Orm\Fields\DecimalField;
use Mindy\Orm\Model;

class Country extends Model
{
    public static function getFields()
    {
        return [
            'iso' => [
                'class' => CharField::class,
                'length' => 2,
            ],
            'continent' => [
                'class' => CharField::class,
                'length' => 2,
            ],
            'name_ru' => [
                'class' => CharField::class,
                'length' => 128,
            ],
            'name_en' => [
                'class' => CharField::class,
                'length' => 128,
            ],
            'lat' => [
                'class' => DecimalField::class,
                'precision' => 6,
                'scale' => 2,
            ],
            'lon' => [
                'class' => DecimalField::class,
                'precision' => 6,
                'scale' => 2,
            ],
            'timezone' => [
                'class' => CharField::class,
                'length' => 30,
            ],
        ];
    }

    public function __toString()
    {
        return (string) $this->name_ru;
    }
}
