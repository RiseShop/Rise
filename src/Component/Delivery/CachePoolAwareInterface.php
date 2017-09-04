<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Delivery;

use Psr\Cache\CacheItemPoolInterface;

interface CachePoolAwareInterface
{
    /**
     * @param CacheItemPoolInterface $cachePool
     */
    public function setCacheItemPool(CacheItemPoolInterface $cachePool);

    /**
     * @return CacheItemPoolInterface
     */
    public function getCacheItemPool();
}
