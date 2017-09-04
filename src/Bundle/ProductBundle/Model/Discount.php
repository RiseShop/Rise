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
use Mindy\Orm\Fields\IntField;
use Mindy\Orm\Fields\TextField;
use Mindy\Orm\Model;

/**
 * Class Discount.
 *
 * @property int $discount
 * @property string $name
 * @property string $description
 * @property \Mindy\Orm\Manager $products
 * todo use http://querybuilder.js.org/
 */
class Discount extends Model
{
    public static function getFields()
    {
        return [
            'name' => [
                'class' => CharField::class,
                'verboseName' => 'Название'
            ],
            'description' => [
                'class' => TextField::class,
                'verboseName' => 'Описание'
            ],
            'discount' => [
                'class' => IntField::class,
                'default' => 10,
                'verboseName' => 'Процент скидки'
            ],
            'variants' => [
                'class' => HasManyField::class,
                'modelClass' => Variant::class,
                'link' => ['discount_id', 'id'],
                'editable' => false,
                'verboseName' => 'Товары'
            ],
        ];
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    public function applyPrice($price)
    {
        return $price - ($this->discount * ($price / 100));
    }
}
