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
use Mindy\Orm\Fields\ForeignField;
use Mindy\Orm\Fields\HasManyField;
use Mindy\Orm\Fields\ManyToManyField;
use Mindy\Orm\Model;
use Mindy\QueryBuilder\Q\QOr;

/**
 * Class Product.
 *
 * @method static \Rise\Bundle\ProductBundle\Model\ProductManager objects($instance = null)
 *
 * @property string $name
 * @property string $sku
 * @property float $price
 * @property Entity $entity
 * @property float $price_source
 * @property Discount|null $discount
 * @property \Mindy\Orm\Manager $images
 * @property \Mindy\Orm\TreeManager $category
 * @property \Mindy\Orm\Manager $variation
 */
class Product extends Model
{
    public static function getFields()
    {
        return [
            'name' => [
                'class' => CharField::class,
                'verboseName' => 'Название'
            ],
            'entity' => [
                'class' => ForeignField::class,
                'modelClass' => Entity::class,
                'null' => true,
                'verboseName' => 'Сущность'
            ],
            /*
             * Похожие товары
             */
            'related' => [
                'class' => ManyToManyField::class,
                'modelClass' => self::class,
                'through' => ProductRelatedThrough::class,
                'link' => ['from_id', 'to_id'],
                'verboseName' => 'Похожие товары'
            ],
            /*
             * Продукт может быть в нескольких категориях сразу.
             * Требуется для сортировки товаров относительно категории.
             * Вывод товаров с учетом сортировки осуществляется только относительно
             * текущей просматриваемой категории. Если вы выводите товары из категории и из подкатегорий,
             * то сортировка не может быть применена корректно, так как будут два товара с позицией 1, восемь
             * с позицией 2 и так далее.
             */
            'category' => [
                'class' => ManyToManyField::class,
                'through' => ProductCategoryThrough::class,
                'link' => ['product_id', 'category_id'],
                'modelClass' => Category::class,
                'verboseName' => 'Категории'
            ],
            'images' => [
                'class' => HasManyField::class,
                'link' => ['product_id', 'id'],
                'modelClass' => Image::class,
                'editable' => false,
                'verboseName' => 'Изображения'
            ],
            /*
             * Характеристики товара принадлежат только одной модели товара и товар отображается
             * на сайте вместе с его вариациями. Причем товары не отображаются в списке товаров, а отображаются их
             * is_master варианты. У каждой вариации есть свои изображения, SKU, своя цена.
             * В целом вариант это независимый товар.
             *
             * Пример: vendor_code (артикул) у велосипеда с корзинкой 123foo, тогда торговое предложение(sku) 123foo,
             * у его варианта без корзинки 123foo, когда торговое предложение 123foo-bar
             */
            'variants' => [
                'class' => HasManyField::class,
                'modelClass' => Variant::class,
                'link' => ['product_id', 'id'],
                'editable' => false,
                'verboseName' => 'Варианты товара'
            ],
        ];
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    public function __get($name)
    {
        if ($name === 'image') {
            return $this->getImage();
        }

        return parent::__get($name);
    }

    /**
     * @return array|Image[]
     */
    public function getImages()
    {
        return $this->images->order(['position'])->all();
    }

    /**
     * @return null|Image
     */
    public function getImage()
    {
        if ($this->images->count() > 0) {
            return $this->images->order(['position'])->limit(1)->get();
        }
    }

    public function getTotalPrice()
    {
        if (empty($this->price)) {
            return $this->price_source;
        }

        return $this->price;
    }

    /**
     * @param Product $owner
     */
    public function afterDelete($owner)
    {
        foreach ($owner->images->all() as $image) {
            /* @var Image $image */
            $image->delete();
        }

        foreach ($owner->variants->all() as $variant) {
            /* @var Variant $variant */
            $variant->delete();
        }

        $categoryLink = ProductCategoryThrough::objects()
            ->filter(['product' => $owner])
            ->all();
        foreach ($categoryLink as $link) {
            /* @var ProductCategoryThrough $link */
            $link->delete();
        }

        $related = ProductRelatedThrough::objects()
            ->filter(new QOr([
                ['from' => $owner],
                ['to' => $owner],
            ]))
            ->all();
        foreach ($related as $link) {
            /* @var ProductRelatedThrough $variant */
            $link->delete();
        }
    }
}
