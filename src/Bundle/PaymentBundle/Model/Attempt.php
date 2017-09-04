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
use Mindy\Orm\Fields\DateTimeField;
use Mindy\Orm\Fields\ForeignField;
use Mindy\Orm\Fields\TextField;
use Mindy\Orm\Model;
use Rise\Bundle\OrderBundle\Model\Order;

/**
 * Class Attempt.
 *
 * @property Payment $payment
 * @property int $payment_id
 * @property Order $order
 * @property int $order_id
 * @property string $code
 * @property bool|int $is_success
 * @property bool|int $is_fail
 * @property bool|int $is_cancel
 * @property int $transaction_id
 * @property string $fail_error
 * @property \DateTime $created_at
 */
class Attempt extends Model implements AttemptInterface
{
    public static function getFields()
    {
        return [
            'payment' => [
                'class' => ForeignField::class,
                'modelClass' => Payment::class,
                'verboseName' => 'Платежная система'
            ],
            'order' => [
                'class' => ForeignField::class,
                'modelClass' => Order::class,
                'verboseName' => 'Заказ'
            ],
            'is_success' => [
                'class' => BooleanField::class,
                'editable' => false,
                'verboseName' => 'Выполнено'
            ],
            'is_fail' => [
                'class' => BooleanField::class,
                'editable' => false,
                'verboseName' => 'Провалено'
            ],
            'fail_error' => [
                'class' => TextField::class,
                'null' => true,
                'editable' => false,
                'verboseName' => 'Текст ошибки'
            ],
            'is_cancel' => [
                'class' => BooleanField::class,
                'editable' => false,
                'verboseName' => 'Отменено'
            ],
            'created_at' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
                'editable' => false,
                'verboseName' => 'Дата создания'
            ],
            'transaction_id' => [
                'class' => CharField::class,
                'editable' => false,
                'null' => true,
                'verboseName' => 'Номер транзакции'
            ],
        ];
    }

    /**
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }
}
