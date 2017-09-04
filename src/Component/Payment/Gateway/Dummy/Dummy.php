<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Payment\Gateway\Dummy;

use Rise\Component\Payment\AbstractGateway;
use Rise\Component\Payment\OfflineGatewayInterface;
use Rise\Component\Payment\OfflinePurchaseResponse;
use Rise\Component\Payment\PurchaseParametersInterface;
use Rise\Component\Payment\ResponseInterface;

/**
 * Class Dummy.
 */
class Dummy extends AbstractGateway implements OfflineGatewayInterface
{
    public function getName()
    {
        return 'Dummy';
    }

    /**
     * @param PurchaseParametersInterface $parameters
     *
     * @return ResponseInterface
     */
    public function purchase(PurchaseParametersInterface $parameters)
    {
        return new OfflinePurchaseResponse();
    }
}
