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

class ProductRelatedThrough extends Model
{
    public static function getFields()
    {
        return [
            'from' => [
                'class' => ForeignField::class,
                'modelClass' => Product::class,
            ],
            'to' => [
                'class' => ForeignField::class,
                'modelClass' => Product::class,
            ],
        ];
    }
}
