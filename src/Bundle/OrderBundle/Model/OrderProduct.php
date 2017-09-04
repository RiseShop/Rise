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
use Mindy\Orm\Fields\DecimalField;
use Mindy\Orm\Fields\ForeignField;
use Mindy\Orm\Fields\IntField;
use Mindy\Orm\Fields\TextField;
use Mindy\Orm\Model;
use Rise\Bundle\ProductBundle\Model\Variant;

/**
 * Class OrderProduct
 *
 * @property float $price_discount
 * @property float $price
 * @property float $price_total
 * @property string|int|float $discount
 * @property int $quantity
 */
class OrderProduct extends Model
{
    public static function getFields()
    {
        return [
            'name' => [
                'class' => CharField::class,
            ],
            'sku' => [
                'class' => CharField::class,
            ],
            'order' => [
                'class' => ForeignField::class,
                'modelClass' => Order::class,
            ],
            /*
             * Если вариант товара не указан или null (отсутствует в бд, но variant_id ссылается на что либо),
             * то помечаем товар как отсутствующий. Используется только для отображения
             * пользователю на сайте описания, связанных картинок и прочего.
             */
            'variant' => [
                'class' => ForeignField::class,
                'modelClass' => Variant::class,
                'null' => true,
            ],
            // Характеристики купленного товара
            'params' => [
                'class' => TextField::class,
                'default' => '[]',
            ],
            'discount' => [
                'class' => IntField::class,
                'default' => 0,
            ],
            // Комментарий менеджера
            'comment' => [
                'class' => TextField::class,
                'null' => true,
            ],
            'quantity' => [
                'class' => IntField::class,
                'default' => 1,
            ],
            'price' => [
                'class' => DecimalField::class,
                'precision' => 10,
                'scale' => 2,
            ],
            // Общая сумма с учетом скидки
            'price_total' => [
                'class' => DecimalField::class,
                'precision' => 10,
                'scale' => 2,
            ],
            // Сумма скидки
            'price_discount' => [
                'class' => DecimalField::class,
                'precision' => 10,
                'scale' => 2,
            ],
        ];
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * @param OrderProduct $owner
     * @param bool         $isNew
     */
    public function beforeSave($owner, $isNew)
    {
        /*
         * Цены актуальны на момент покупки.
         *
         * Если пользователь в течении акции положил товар в корзину и оформил заказ со стоимостью
         * 99 рублей, когда номинальная стоимость 100, то именно с такой стоимостью мы и должны отпустить
         * ему товар.
         */
        if ($owner->discount > 0) {
            if (strpos($this->discount, '%') === false) {
                $owner->price_discount = $this->discount;
            } else {
                $owner->price_discount = ($this->quantity * $this->price) * ($this->discount / 100);
            }
        }
        $owner->price_total = ($this->quantity * $this->price) - $this->price_discount;
    }

    public function getParameters()
    {
        if (empty($this->params) || in_array($this->params, ['[]', '{}'])) {
            return [];
        }

        return json_decode($this->params, true);
    }
}
