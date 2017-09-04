<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\GeoBundle\EventListener;

use Rise\Bundle\GeoBundle\Geo\Geo;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class GeoListener
{
    protected $geo;

    public function __construct(Geo $geo)
    {
        $this->geo = $geo;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();
        if ($this->geo->detect($request->getClientIp())) {
        }
    }
}
