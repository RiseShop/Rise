<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\PaymentBundle\Model;

use Mindy\Orm\Fields\BooleanField;
use Mindy\Orm\Fields\CharField;
use Mindy\Orm\Fields\PositionField;
use Mindy\Orm\Fields\TextField;
use Mindy\Orm\Model;

/**
 * Class Payment.
 *
 * @property string $gateway
 *
 * @method static \Rise\Bundle\PaymentBundle\Model\PaymentManager objects($instance = null)
 */
class Payment extends Model
{
    public static function getFields()
    {
        return [
            'gateway' => [
                'class' => CharField::class,
                'unique' => true,
                'verboseName' => 'Шлюз'
            ],
            'name' => [
                'class' => CharField::class,
                'verboseName' => 'Название для пользователя'
            ],
            'description' => [
                'class' => TextField::class,
                'verboseName' => 'Описание шлюза для пользователя'
            ],
            'is_enabled' => [
                'class' => BooleanField::class,
                'default' => true,
                'verboseName' => 'Включено'
            ],
            'position' => [
                'class' => PositionField::class,
                'verboseName' => 'Позиция'
            ],
        ];
    }

    public function __toString()
    {
        return (string) $this->name;
    }
}
