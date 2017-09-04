<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\CartBundle\Model;

use Mindy\Orm\Fields\DateTimeField;
use Mindy\Orm\Fields\ForeignField;
use Mindy\Orm\Model;
use Rise\Bundle\ProductBundle\Model\Variant;
use Rise\Bundle\UserBundle\Model\User;

class Wish extends Model
{
    public static function getFields()
    {
        return [
            'created_at' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
                'verboseName' => 'Дата создания'
            ],
            'variant' => [
                'class' => ForeignField::class,
                'modelClass' => Variant::class,
                'verboseName' => 'Товар'
            ],
            'user' => [
                'class' => ForeignField::class,
                'modelClass' => User::class,
                'verboseName' => 'Пользователь'
            ],
        ];
    }
}
