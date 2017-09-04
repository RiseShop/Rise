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
 * Class VariantManager.
 *
 * @method Variant|null get($condition = null)
 */
class VariantManager extends Manager
{
    public function published()
    {
        $this->filter(['status' => Variant::STATUS_PUBLISHED]);

        return $this;
    }

    public function draftOrPublished()
    {
        $this->filter([
            'status__in' => [
                Variant::STATUS_DRAFT,
                Variant::STATUS_PUBLISHED,
            ],
        ]);

        return $this;
    }

    public function master()
    {
        $this->filter(['is_master' => true]);

        return $this;
    }
}
