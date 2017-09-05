<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\CartBundle\Model;

use Mindy\Orm\Fields\BooleanField;
use Mindy\Orm\Fields\CharField;
use Mindy\Orm\Fields\DateTimeField;
use Mindy\Orm\Fields\ForeignField;
use Mindy\Orm\Fields\HasManyField;
use Mindy\Orm\Model;
use Rise\Bundle\UserBundle\Model\User;
use Rise\Component\Cart\CartStorageInterface;

/**
 * Class Cart.
 *
 * @property \Mindy\Orm\Manager $positions
 * @property string $session_id
 * @property User $user
 * @property int $user_id
 * @property bool $is_active
 * @property \DateTime $created_at
 */
class Cart extends Model implements CartStorageInterface
{
    public static function getFields()
    {
        return [
            'user' => [
                'class' => ForeignField::class,
                'modelClass' => User::class,
                'null' => true,
                'verboseName' => 'Пользователь'
            ],
            'session_id' => [
                'class' => CharField::class,
                'editable' => false,
                'length' => 32,
                'verboseName' => 'ID сессии'
            ],
            'positions' => [
                'class' => HasManyField::class,
                'modelClass' => Position::class,
                'link' => ['cart_id', 'id'],
                'verboseName' => 'Позиции'
            ],
            'is_active' => [
                'class' => BooleanField::class,
                'default' => false,
                'verboseName' => 'Активно'
            ],
            'created_at' => [
                'class' => DateTimeField::class,
                'autoNowAdd' => true,
                'verboseName' => 'Дата создания'
            ],
        ];
    }

    public function __toString()
    {
        return sprintf("Корзина покупателя №%s от %s", $this->id, $this->created_at);
    }

    /*
     * todo add validation to cart component
     */
//    public function isValid() : bool
//    {
//        $isValid = parent::isValid();
//
//        $errors = $this->getErrors();
//        $validator = Validation::createValidatorBuilder()->getValidator();
//
//        $constraints = [];
//        $value = '';
//        $errors = array_merge($errors, $validator->validate($value, $constraints));
//
//        $this->setErrors($errors);
//        return count($this->getErrors()) === 0;
//    }

    /**
     * {@inheritdoc}
     */
    public function getPositions()
    {
        return $this->positions->all();
    }

    /**
     * {@inheritdoc}
     */
    public function removePosition($key)
    {
        $this->positions->filter(['unique_id' => $key])->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function hasPosition($key)
    {
        return $this->positions->filter(['unique_id' => $key])->count() > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function getPosition($key)
    {
        return $this->positions->get(['unique_id' => $key]);
    }

    /**
     * Remove all positions from cart
     */
    public function clear()
    {
        $this->positions->delete();
    }
}
