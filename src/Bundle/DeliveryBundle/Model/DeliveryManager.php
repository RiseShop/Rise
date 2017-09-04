<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\DeliveryBundle\Model;

use Mindy\Orm\Manager;

class DeliveryManager extends Manager
{
    public function enabled()
    {
        $this->filter(['is_enabled' => true]);

        return $this;
    }
}
