<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\DeliveryBundle\Model;

use Mindy\Orm\Fields\ForeignField;
use Mindy\Orm\Model;
use Rise\Bundle\PaymentBundle\Model\Payment;

class DeliveryPaymentThrough extends Model
{
    public static function getFields()
    {
        return [
            'delivery' => [
                'class' => ForeignField::class,
                'modelClass' => Delivery::class,
            ],
            'payment' => [
                'class' => ForeignField::class,
                'modelClass' => Payment::class,
            ],
        ];
    }
}
