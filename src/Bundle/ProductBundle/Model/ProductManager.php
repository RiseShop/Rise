<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\ProductBundle\Model;

use Mindy\Orm\Manager;

/**
 * Class ProductManager.
 *
 * @method \Mindy\Bundle\ShopBundle\Model\Product\Product get($conditions = [])
 */
class ProductManager extends Manager
{
    public function published()
    {
        $this->filter(['is_published' => true]);

        return $this;
    }

    public function related()
    {
        $ids = $this->getModel()->category->valuesList(['id'], true);
        if (empty($ids)) {
            $this->order(['?']);
        } else {
            $this->filter(['category__id__in' => $ids]);
        }
        $this->exclude(['id' => $this->getModel()->pk]);

        return $this;
    }
}
