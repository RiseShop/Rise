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

/**
 * Class Value.
 *
 * @property string $name
 * @property string|int|float $value
 * @property string $attribute_code
 * @property int $attribute_id
 * @property Attribute $attribute
 */
class Value extends Model
{
    public static function getFields()
    {
        return [
            'attr' => [
                'class' => ForeignField::class,
                'modelClass' => Attribute::class,
                'verboseName' => 'Аттрибут'
            ],
            'value' => [
                'class' => CharField::class,
                'verboseName' => 'Значение'
            ],
        ];
    }

    public function __toString()
    {
        return (string) $this->value;
    }
}
