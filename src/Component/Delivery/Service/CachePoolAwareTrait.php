<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Delivery\Service;

use Psr\Cache\CacheItemPoolInterface;

trait CachePoolAwareTrait
{
    /**
     * @var CacheItemPoolInterface
     */
    protected $cachePool;

    /**
     * @param CacheItemPoolInterface $cachePool
     */
    public function setCacheItemPool(CacheItemPoolInterface $cachePool)
    {
        $this->cachePool = $cachePool;
    }

    /**
     * @return CacheItemPoolInterface
     */
    public function getCacheItemPool()
    {
        return $this->cachePool;
    }
}
