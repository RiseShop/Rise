<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Cart;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\User\UserInterface;

class Cart implements CartInterface
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * @var CartStorageInterface
     */
    protected $storage;

    /**
     * @var DiscountInterface[]
     */
    protected $discounts = [];

    /**
     * Cart constructor.
     *
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param DiscountInterface $discount
     *
     * @return $this
     */
    public function addDiscount(DiscountInterface $discount)
    {
        $this->discounts[] = $discount;

        return $this;
    }

    /**
     * @return PositionInterface[]
     */
    public function getPositions()
    {
        return $this->storage->getPositions();
    }

    /**
     * {@inheritdoc}
     */
    public function getQuantity()
    {
        return array_sum(array_map(function (PositionInterface $position) {
            return $position->getQuantity();
        }, $this->getPositions()));
    }

    /**
     * Apply discounts.
     *
     * @param UserInterface|null $user
     *
     * @return float
     */
    public function applyDiscounts(UserInterface $user = null)
    {
        $price = $this->getPrice();
        foreach ($this->discounts as $discount) {
            if ($discount->supports($this, $user)) {
                $price = $discount->apply($price);
            }
        }

        return $price;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return array_sum(array_map(function (PositionInterface $position) {
            return $position->getPrice();
        }, $this->getPositions()));
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalPrice(UserInterface $user = null)
    {
        return $this->applyDiscounts($user);
    }

    /**
     * @param $key
     * @param PositionInterface $position
     */
    public function addPosition($key, PositionInterface $position)
    {
        $position->setCartStorage($this->storage);
    }

    /**
     * @param $key
     */
    public function removePosition($key)
    {
        $this->storage->removePosition($key);
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function hasPosition($key)
    {
        return $this->storage->hasPosition($key);
    }

    /**
     * @param CartStorageInterface $storage
     */
    public function setStorage(CartStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param string $key
     *
     * @return PositionInterface
     */
    public function getPosition($key)
    {
        return $this->storage->getPosition($key);
    }

    /**
     * Remove all positions from cart
     */
    public function clear()
    {
        $this->storage->clear();
    }
}
