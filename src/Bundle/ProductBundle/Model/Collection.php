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

class Collection extends Model
{
    public static function getFields()
    {
        return [
            'name' => [
                'class' => CharField::class,
                'verboseName' => 'Название'
            ],
            'products' => [
                'class' => ManyToManyField::class,
                'modelClass' => Product::class,
                'through' => CollectionVariantThrough::class,
                'link' => ['collection_id', 'product_variant_id'],
                'verboseName' => 'Товары'
            ],
        ];
    }

    public function __toString()
    {
        return (string) $this->name;
    }
}
