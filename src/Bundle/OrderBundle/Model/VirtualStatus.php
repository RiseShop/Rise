<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\OrderBundle\Model;

/**
 * Class VirtualStatus.
 *
 * @property int $id
 * @property int $pk
 * @property string $name
 * @property string $color
 */
class VirtualStatus
{
    protected $id;
    protected $name;
    protected $color;

    public function __construct($id, $name, $color)
    {
        $this->id = $id;
        $this->name = $name;
        $this->color = $color;
    }

    public function __get($name)
    {
        if ($name === 'pk') {
            $name = 'id';
        }
        if (in_array($name, ['id', 'name', 'color'])) {
            return $this->{$name};
        }

        throw new \Exception('Unknown property: '.$name);
    }

    public function __toString()
    {
        return $this->name;
    }
}
