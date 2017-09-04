<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\OrderBundle\Model;

use Mindy\Orm\Fields\CharField;
use Mindy\Orm\Fields\DateTimeField;
use Mindy\Orm\Fields\DecimalField;
use Mindy\Orm\Fields\ForeignField;
use Mindy\Orm\Fields\HasManyField;
use Mindy\Orm\Fields\IntField;
use Mindy\Orm\Fields\TextField;
use Mindy\Orm\Model;
use Ramsey\Uuid\Uuid;
use Rise\Bundle\DeliveryBundle\Model\Delivery;
use Rise\Bundle\PaymentBundle\Model\Payment;
use Rise\Bundle\UserBundle\Model\User;

/**
 * Class Order.
 *
 * @property Customer $customer
 * @property Payment $payment
 * @property int|string $customer_id
 * @property int|string $id
 * @property string $order
 * @property string $payment_order_id
 * @property int $status_id
 * @property \Mindy\Orm\Manager $products
 * @property Status $status
 * @property float $price
 * @property float $price_delivery
 * @property float $price_total
 * @property int $discount
 * @property string $token UUIDv4
 */
class Order extends Model implements OrderInterface
{
    const STATUS_NEW = -1;
    const STATUS_COMPLETE = -2;
    const STATUS_VERIFICATION = -3;
    const STATUS_PAIDED = -4;
    const STATUS_CANCELLED = -5;
    const STATUS_DRAFT = -100;
    const STATUS_PRE_PAIDED = -6;

    public static function getFields()
    {
        return [
            'status_id' => [
                'class' => IntField::class,
                'verboseName' => 'Статус',
            ],
            'customer' => [
                'class' => ForeignField::class,
                'modelClass' => Customer::class,
                'verboseName' => 'Клиент',
            ],
            'delivery' => [
                'class' => ForeignField::class,
                'modelClass' => Delivery::class,
                'null' => true,
                'verboseName' => 'Способ доставки',
            ],
            'payment' => [
                'class' => ForeignField::class,
                'modelClass' => Payment::class,
                'null' => true,
                'verboseName' => 'Способ оплаты',
            ],
            'user' => [
                'class' => ForeignField::class,
                'modelClass' => User::class,
                'null' => true,
                'verboseName' => 'Пользователь',
            ],
            'comment' => [
                'class' => TextField::class,
                'editable' => false,
                'verboseName' => 'Комментарий',
            ],
            'discount' => [
                'class' => IntField::class,
                'default' => 0,
                'verboseName' => 'Скидка',
            ],
            'price_delivery' => [
                'class' => DecimalField::class,
                'precision' => 10,
                'scale' => 2,
                'verboseName' => 'Стоимость доставки',
            ],
            'created_at' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
                'editable' => false,
                'verboseName' => 'Дата создания',
            ],
            'track_number' => [
                'class' => CharField::class,
                'null' => true,
                'verboseName' => 'Номер отслеживания',
            ],
            'token' => [
                'class' => CharField::class,
                'length' => 36,
                'null' => false,
                'unique' => true,
                'verboseName' => 'Токен',
            ],
            'products' => [
                'class' => HasManyField::class,
                'modelClass' => OrderProduct::class,
                'link' => ['order_id', 'id'],
                'editable' => false,
                'verboseName' => 'Товары',
            ],
            'comments' => [
                'class' => HasManyField::class,
                'modelClass' => Comment::class,
                'link' => ['order_id', 'id'],
                'editable' => false,
                'verboseName' => 'Комментарии',
            ],
            'payment_order_id' => [
                'class' => CharField::class,
                'editable' => false,
                'null' => true,
                'verboseName' => 'Номер транзакции в платежном шлюзе',
            ],
        ];
    }

    public function __toString()
    {
        return sprintf('Заказ №%s', $this->id);
    }

    public function __get($name)
    {
        if ($name === 'status') {
            return $this->getStatus();
        } elseif ($name === 'price_discount') {
            return $this->getPriceDiscount();
        } elseif ($name === 'price_total') {
            return $this->getPriceTotal();
        }

        return parent::__get($name);
    }

    public function getPriceDiscount()
    {
        if ($this->discount > 0) {
            return $this->discount * ($this->getPrice() / 100);
        }

        return 0;
    }

    public function getPriceTotal()
    {
        return ($this->getPrice() - $this->getPriceDiscount()) + $this->getPriceDelivery();
    }

    private static function getDefaultStatuses()
    {
        return array_map(function (array $item) {
            return new VirtualStatus($item['id'], $item['name'], $item['color']);
        }, [
            ['id' => self::STATUS_DRAFT, 'name' => 'Черновик', 'color' => 'gray'],
            ['id' => self::STATUS_NEW, 'name' => 'Новый', 'color' => 'red'],
            ['id' => self::STATUS_COMPLETE, 'name' => 'Завершен', 'color' => 'gray'],
            ['id' => self::STATUS_VERIFICATION, 'name' => 'Новый заказ. Проверка заказа менеджером', 'color' => 'blue'],
            ['id' => self::STATUS_PAIDED, 'name' => 'Оплачен', 'color' => 'green'],
            ['id' => self::STATUS_CANCELLED, 'name' => 'Отменен пользователем', 'color' => 'black'],
        ]);
    }

    /**
     * @return array
     */
    public static function getStatuses()
    {
        return array_merge(self::getDefaultStatuses(), Status::objects()->all());
    }

    /**
     * @return array
     */
    public static function getStatusChoices()
    {
        $statuses = [];
        foreach (self::getStatuses() as $status) {
            $statuses[(string) $status] = $status->pk;
        }

        return $statuses;
    }

    /**
     * @return null|VirtualStatus|Status
     */
    public function getStatus()
    {
        $statuses = self::getStatuses();
        foreach ($statuses as $status) {
            if ($status->pk == $this->status_id) {
                return $status;
            }
        }

        return null;
    }

    public function beforeSave($owner, $isNew)
    {
        if ($isNew) {
            $owner->token = Uuid::uuid4()->toString();
        }
    }

    public function getPriceDelivery()
    {
        return $this->price_delivery;
    }

    public function getPrice()
    {
        return array_sum(array_map(function (OrderProduct $product) {
            return $product->price_total;
        }, $this->products->all()));
    }

    /**
     * @return string|int
     */
    public function getId()
    {
        return $this->id;
    }
}
