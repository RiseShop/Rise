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
use Mindy\Orm\Fields\ForeignField;
use Mindy\Orm\Model;

class City extends Model
{
    public static function getFields()
    {
        return [
            'region' => [
                'class' => ForeignField::class,
                'modelClass' => Region::class,
                'null' => true,
                'length' => 8,
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
                'precision' => 10,
                'scale' => 5,
            ],
            'lon' => [
                'class' => DecimalField::class,
                'precision' => 10,
                'scale' => 5,
            ],
            'okato' => [
                'class' => CharField::class,
                'null' => true,
                'length' => 20,
            ],
        ];
    }

    public function __toString()
    {
        return (string) $this->name_ru;
    }
}
