<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\ProductBundle\Model;

use Mindy\Orm\Fields\CharField;
use Mindy\Orm\Fields\ForeignField;
use Mindy\Orm\Model;

class ValueVariant extends Model
{
    public static function getFields()
    {
        return [
            'variant' => [
                'class' => ForeignField::class,
                'modelClass' => Variant::class,
                'verboseName' => 'Вариант товара'
            ],
            'attr' => [
                'class' => ForeignField::class,
                'modelClass' => Attribute::class,
                'verboseName' => 'Аттрибут'
            ],
            'attribute_code' => [
                'class' => CharField::class,
                'editable' => false,
                'verboseName' => 'Код аттрибута'
            ],
            'value' => [
                'class' => ForeignField::class,
                'modelClass' => Value::class,
                'verboseName' => 'Значение'
            ],
        ];
    }
}
