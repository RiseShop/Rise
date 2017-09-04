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
use Mindy\Orm\Fields\ManyToManyField;
use Mindy\Orm\Model;

/**
 * Class ProductClass.
 *
 * @property string $name
 * @property \Mindy\Orm\Manager $product_attributes
 */
class Entity extends Model
{
    public static function getFields()
    {
        return [
            'name' => [
                'class' => CharField::class,
                'verboseName' => 'Название'
            ],
            'attributes' => [
                'class' => ManyToManyField::class,
                'modelClass' => Attribute::class,
                'through' => EntityThrough::class,
                'editable' => false,
                'link' => ['group_id', 'attr_id'],
                'verboseName' => 'Аттрибуты'
            ],
        ];
    }

    public function __toString()
    {
        return (string) $this->name;
    }
}
