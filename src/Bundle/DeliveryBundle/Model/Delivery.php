<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\DeliveryBundle\Model;

use Mindy\Orm\Fields\BooleanField;
use Mindy\Orm\Fields\CharField;
use Mindy\Orm\Fields\ManyToManyField;
use Mindy\Orm\Fields\PositionField;
use Mindy\Orm\Fields\TextField;
use Mindy\Orm\Model;
use Rise\Bundle\PaymentBundle\Model\Payment;

/**
 * Class Delivery.
 *
 * @method static \Rise\Bundle\DeliveryBundle\Model\DeliveryManager objects($instance = null);
 */
class Delivery extends Model
{
    public static function getFields()
    {
        return [
            'service' => [
                'class' => CharField::class,
                'verboseName' => 'Служба доставки'
            ],
            'payment' => [
                'class' => ManyToManyField::class,
                'modelClass' => Payment::class,
                'through' => DeliveryPaymentThrough::class,
                'link' => ['delivery_id', 'payment_id'],
                'verboseName' => 'Платежные системы'
            ],
            'name' => [
                'class' => CharField::class,
                'verboseName' => 'Название для пользователя'
            ],
            'description' => [
                'class' => TextField::class,
                'verboseName' => 'Описание для пользователя'
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
