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
use Mindy\Orm\Fields\PositionField;
use Mindy\Orm\Model;
use Mindy\Orm\ModelInterface;

class ProductCategoryThrough extends Model
{
    public static function getFields()
    {
        return [
            'product' => [
                'class' => ForeignField::class,
                'modelClass' => Product::class,
            ],
            'category' => [
                'class' => ForeignField::class,
                'modelClass' => Category::class,
            ],
            'position' => [
                'class' => PositionField::class,
                'callback' => function (ModelInterface $model) {
                    return $model->objects()->filter([
                        'category_id' => $model->category_id,
                    ]);
                },
            ],
        ];
    }
}
