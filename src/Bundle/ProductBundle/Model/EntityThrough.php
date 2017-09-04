<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\ProductBundle\Model;

use Mindy\Orm\Fields\ForeignField;
use Mindy\Orm\Model;

class EntityThrough extends Model
{
    public static function getFields()
    {
        return [
            'attr' => [
                'class' => ForeignField::class,
                'modelClass' => Attribute::class,
            ],
            'group' => [
                'class' => ForeignField::class,
                'modelClass' => Entity::class,
            ],
        ];
    }
}
