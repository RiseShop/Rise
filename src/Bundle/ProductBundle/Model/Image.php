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
use Mindy\Orm\Fields\ImageField;
use Mindy\Orm\Fields\PositionField;
use Mindy\Orm\Model;

/**
 * Class Image.
 *
 * @property Variant $product_variant
 * @property int $product_variant_id
 * @property string $image
 */
class Image extends Model
{
    public static function getFields()
    {
        return [
            'image' => [
                'class' => ImageField::class,
                'null' => false,
                'verboseName' => 'Изображение'
            ],
            'product' => [
                'class' => ForeignField::class,
                'modelClass' => Product::class,
                'verboseName' => 'Товар'
            ],
            'position' => [
                'class' => PositionField::class,
                'callback' => function (Image $model) {
                    return $model->objects()->filter([
                        'product_id' => $model->product_id,
                    ]);
                },
                'verboseName' => 'Позиция'
            ],
        ];
    }
}
