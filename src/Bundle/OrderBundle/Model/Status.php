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
use Mindy\Orm\Model;

/**
 * Class Status.
 *
 * @property string $name
 * @property string $color
 */
class Status extends Model
{
    public static function getFields()
    {
        return [
            'name' => [
                'class' => CharField::class,
            ],
            'color' => [
                'class' => CharField::class,
                'length' => 7,
            ],
        ];
    }

    public function __toString()
    {
        return (string) $this->name;
    }
}
