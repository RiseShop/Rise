<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\OrderBundle\Model;

use Mindy\Orm\Fields\DateTimeField;
use Mindy\Orm\Fields\FileField;
use Mindy\Orm\Fields\ForeignField;
use Mindy\Orm\Fields\TextField;
use Mindy\Orm\Model;
use Rise\Bundle\UserBundle\Model\User;

/**
 * Class Comment
 * @package Rise\Bundle\OrderBundle\Model
 * @property string|\DateTime $created_at
 * @property string $file
 * @property string $comment
 * @property string|int $user_id
 * @property object $user
 * @property Order $order
 * @property string|int $order_id
 */
class Comment extends Model
{
    public static function getFields()
    {
        return [
            'order' => [
                'class' => ForeignField::class,
                'modelClass' => Order::class,
                'verboseName' => 'Заказ'
            ],
            'user' => [
                'class' => ForeignField::class,
                'modelClass' => User::class,
                'null' => true,
                'verboseName' => 'Пользователь'
            ],
            'comment' => [
                'class' => TextField::class,
                'verboseName' => 'Комментарий'
            ],
            'file' => [
                'class' => FileField::class,
                'null' => true,
                'verboseName' => 'Файл'
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
        return (string) $this->comment;
    }
}
