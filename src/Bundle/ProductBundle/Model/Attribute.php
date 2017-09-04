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
use Mindy\Orm\Fields\HasManyField;
use Mindy\Orm\Fields\ManyToManyField;
use Mindy\Orm\Model;

/**
 * Class Attribute.
 *
 * @property \Mindy\Orm\Manager $value
 * @property string $name
 * @property string $code
 */
class Attribute extends Model
{
    public static function getFields()
    {
        return [
            'name' => [
                'class' => CharField::class,
                'verboseName' => 'Название',
            ],
            'code' => [
                'class' => CharField::class,
                'verboseName' => 'Код',
            ],
            'value' => [
                'editable' => false,
                'class' => HasManyField::class,
                'modelClass' => Value::class,
                'link' => ['attr_id', 'id'],
                'verboseName' => 'Значение',
            ],
            'entities' => [
                'class' => ManyToManyField::class,
                'modelClass' => Entity::class,
                'through' => EntityThrough::class,
                'editable' => false,
                'link' => ['attr_id', 'entity_id'],
                'verboseName' => 'Сущности',
            ],
            'widget' => [
                'class' => CharField::class,
                'choices' => [
                    'select' => 'select',
                    'radio' => 'radio',
                    'checkbox' => 'checkbox',
                ],
                'default' => 'select',
                'verboseName' => 'Виджет',
            ],
        ];
    }

    public function __toString()
    {
        return (string) $this->name;
    }
}
