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
use Mindy\Orm\Fields\SlugField;
use Mindy\Orm\TreeModel;

/**
 * Class Category.
 *
 * @property string $name
 * @property \Mindy\Orm\Manager $product_classes
 */
class Category extends TreeModel
{
    public static function getFields()
    {
        return array_merge(parent::getFields(), [
            'name' => [
                'class' => CharField::class,
                'verboseName' => 'Название'
            ],
            'slug' => [
                'class' => SlugField::class,
                'verboseName' => 'Слаг'
            ],
        ]);
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    public function getAbsoluteUrl()
    {
        return $this->reverse('shop_variant_list', ['slug' => $this->slug]);
    }
}
