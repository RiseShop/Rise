<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\CartBundle\Model;

use Exception;
use Mindy\Orm\Fields\CharField;
use Mindy\Orm\Fields\ForeignField;
use Mindy\Orm\Fields\IntField;
use Mindy\Orm\Fields\TextField;
use Mindy\Orm\Model;
use Rise\Bundle\ProductBundle\Model\Variant;
use Rise\Component\Cart\CartStorageInterface;
use Rise\Component\Cart\PositionInterface;

/**
 * Class Position.
 *
 * @property Cart $cart
 * @property Variant $variant
 * @property string $variant_serialized Serialized version of product variant
 * @property array $params
 * @property string $unique_id
 * @property int $quantity
 */
class Position extends Model implements PositionInterface
{
    public static function getFields()
    {
        return [
            'cart' => [
                'class' => ForeignField::class,
                'modelClass' => Cart::class,
                'editable' => false,
            ],
            'variant' => [
                'class' => ForeignField::class,
                'modelClass' => Variant::class,
                'editable' => false,
                /*
                 * null => true, так как вариант товара может быть удален, а корзина уже оформлена
                 * и оставлена у пользователя в истории корзин. Либо на момент сбора корзины
                 * произошла синхронизация и позиция с таким товаром уже не существует.
                 * В данном случае нам нужно отобразить product_serialized и уведомить пользователя,
                 * что он должен удалить данную позицию из корзины.
                 */
                'null' => true,
            ],
            'variant_serialized' => [
                'class' => TextField::class,
                'editable' => false,
            ],
            'params' => [
                'class' => TextField::class,
                'default' => '[]',
            ],
            'quantity' => [
                'class' => IntField::class,
                'default' => 1,
            ],
            'unique_id' => [
                'class' => CharField::class,
                'null' => false,
                'editable' => false,
            ],
        ];
    }

    /**
     * @param Position $owner
     * @param $isNew
     */
    public function beforeSave($owner, $isNew)
    {
        if ($isNew) {
            $owner->unique_id = $this->generateUniqueId();

            if (empty($owner->params)) {
                $params = [];
            } elseif (is_string($owner->params)) {
                $params = json_decode($owner->params, true);
            } else {
                $params = $owner->params;
            }

            ksort($params);
            $owner->params = json_encode($params);

            $owner->variant_serialized = serialize($owner->variant);
        }
    }

    public function getSerializedProduct()
    {
        return unserialize($this->variant_serialized);
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return (int) $this->quantity;
    }

    /**
     * @return ProductInterface
     */
    public function getProduct()
    {
        return $this->variant;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->getProduct()->getPrice() * $this->getQuantity();
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        if (is_string($this->params)) {
            return json_decode($this->params, true);
        }

        return $this->params;
    }

    /**
     * @param int|string $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        $this->save(['quantity']);
    }

    /**
     * @param PositionInterface $cartLine
     *
     * @throws \Exception
     */
    public function merge(PositionInterface $cartLine)
    {
        if (
            $this->getProduct()->getSku() === $cartLine->getProduct()->getSku() &&
            $this->getParams() === $cartLine->getParams()
        ) {
            $this->quantity += $cartLine->getQuantity();
            $this->save(['quantity']);
        } else {
            throw new Exception('Incorrect cart position to merge');
        }
    }

    /**
     * @return string
     */
    public function generateUniqueId()
    {
        return md5(serialize([
            $this->getProduct()->getSku(),
            $this->params,
        ]));
    }

    /**
     * @param CartStorageInterface $storage
     */
    public function setCartStorage(CartStorageInterface $storage)
    {
        $this->cart = $storage;
        $this->save();
    }
}
