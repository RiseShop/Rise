<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\ProductBundle\Model;

use Mindy\Orm\Fields\BooleanField;
use Mindy\Orm\Fields\CharField;
use Mindy\Orm\Fields\DecimalField;
use Mindy\Orm\Fields\FloatField;
use Mindy\Orm\Fields\ForeignField;
use Mindy\Orm\Fields\HasManyField;
use Mindy\Orm\Fields\IntField;
use Mindy\Orm\Fields\ManyToManyField;
use Mindy\Orm\Fields\SlugField;
use Mindy\Orm\Fields\TextField;
use Mindy\Orm\Model;
use Rise\Component\Cart\ProductInterface;

/**
 * Class Variant.
 *
 * @method static \Rise\Bundle\ProductBundle\Model\VariantManager objects($instance = null)
 *
 * @property string $name
 * @property string $sku
 * @property Product $product
 * @property int $product_id
 * @property bool|int $is_master
 * @property float $price
 * @property float $price_source
 * @property Discount|null $discount
 * @property \Mindy\Orm\Manager $images
 * @property \Mindy\Orm\Manager $value
 */
class Variant extends Model implements ProductInterface
{
    const STATUS_HIDDEN = 0;
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;

    public static function getFields()
    {
        return [
            'product' => [
                'class' => ForeignField::class,
                'modelClass' => Product::class,
                'verboseName' => 'Продукт'
            ],
            'is_master' => [
                'class' => BooleanField::class,
                'default' => false,
                'verboseName' => 'Мастер вариант'
            ],
            'price_source' => [
                'class' => DecimalField::class,
                'precision' => 10,
                'scale' => 2,
                'verboseName' => 'Цена исходная'
            ],
            'price' => [
                'class' => DecimalField::class,
                'precision' => 10,
                'scale' => 2,
                'verboseName' => 'Цена'
            ],
            'name' => [
                'class' => CharField::class,
                'verboseName' => 'Название'
            ],
            'sku' => [
                'class' => CharField::class,
                'null' => true,
                'verboseName' => 'Артикул'
            ],
            'slug' => [
                'class' => SlugField::class,
                'unique' => true,
                'verboseName' => 'Слаг'
            ],
            'status' => [
                'class' => IntField::class,
                'choices' => self::getStatusChoices(),
                'verboseName' => 'Статус'
            ],
            'discount' => [
                'class' => ForeignField::class,
                'modelClass' => Discount::class,
                'null' => true,
                'verboseName' => 'Скидка'
            ],
            /*
             * Доступно для продажи
             */
            'available_for_sale' => [
                'class' => BooleanField::class,
                'default' => true,
                'verboseName' => 'Есть в наличии (доступно для продажи)'
            ],
            /*
             * Контроль остатков этого товара
             */
            'inventory_tracking' => [
                'class' => BooleanField::class,
                'default' => true,
                'verboseName' => 'Контроль остатков на складе'
            ],
            /*
             * Работает только если активирован inventory_tracking_status
             *
             * Итоговое кол-во товара на складах
             * Москва: 10
             * Киров: 15
             *
             * Отображаем 25
             */
            'quantity' => [
                'class' => IntField::class,
                'verboseName' => 'Количество на складе'
            ],
            'description_short' => [
                'class' => TextField::class,
                'verboseName' => 'Краткое описание'
            ],
            'description' => [
                'class' => TextField::class,
                'verboseName' => 'Описание'
            ],
            'weight' => [
                'class' => FloatField::class,
                'verboseName' => 'Вес',
                'default' => 0
            ],
            'values' => [
                'class' => ManyToManyField::class,
                'modelClass' => Value::class,
                'through' => ValueVariant::class,
                'link' => ['variant_id', 'value_id'],
                'editable' => false,
                'verboseName' => 'Значения'
            ],
            'q' => [
                'class' => HasManyField::class,
                'modelClass' => ValueVariant::class,
                'link' => ['variant_id', 'id'],
                'editable' => false,
                'verboseName' => 'Значения'
            ],
        ];
    }

    public static function getStatusChoices()
    {
        return [
            self::STATUS_HIDDEN => 'shop.product_variant.status.hidden',
            self::STATUS_DRAFT => 'shop.product_variant.status.draft',
            self::STATUS_PUBLISHED => 'shop.product_variant.status.published',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function setAttribute($name, $value)
    {
        try {
            parent::setAttribute($name, $value);
        } catch (\Exception $e) {
        }
    }

    public function __get($name)
    {
        if ($name === 'image') {
            return $this->getImage();
        }

        return parent::__get($name);
    }

    public function getImage()
    {
        return $this->product->getImage();
    }

    /**
     * @param Variant $owner
     * @param $isNew
     */
    public function beforeSave($owner, $isNew)
    {
        if ($owner->is_master) {
            /*
             * Если мы выставляем другой вариант основным,
             * то очищаем предыдущие основные варианты
             */
            self::objects()
                ->filter(['product_id' => $owner->product_id])
                ->update(['is_master' => false]);
        }

        if ($owner->discount) {
            $owner->price = $owner->discount->applyPrice($owner->price_source);
        } else {
            $owner->price = $owner->price_source;
        }
    }

    /**
     * @param Variant $owner
     */
    public function afterDelete($owner)
    {
        foreach ($owner->value->all() as $value) {
            /* @var Value $value */
            $value->delete();
        }
    }

    public function getTotalPrice()
    {
        if ($this->discount) {
            return $this->discount->applyPrice($this->price);
        }

        return $this->price;
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @return string|int|float
     */
    public function getPrice()
    {
        return $this->price;
    }

    public function getValues()
    {
        $attributes = [];
        foreach ($this->values->all() as $value) {
            $attributes[$value->attr->name] = $value->value;
        }

        return $attributes;
    }
}
