<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Payment;

use Rise\Component\Payment\Gateway\GatewayInterface;

class Factory
{
    /**
     * @var array|GatewayInterface[]
     */
    protected $gateways = [];

    /**
     * Factory constructor.
     *
     * @param bool $testMode
     */
    public function __construct($testMode = false)
    {
        $this->testMode = $testMode;
    }

    /**
     * @param array|GatewayInterface[] $gateways
     */
    public function setGateways(array $gateways)
    {
        $this->gateways = $gateways;
    }

    /**
     * @param $id
     *
     * @throws \RuntimeException
     *
     * @return GatewayInterface
     */
    public function getGateway($id)
    {
        if (isset($this->gateways[$id])) {
            $gateway = $this->gateways[$id];
            $gateway->setTestMode($this->testMode);

            return $gateway;
        }

        throw new \RuntimeException('Unknown payment gateway');
    }

    /**
     * @return GatewayInterface[]
     */
    public function getGateways()
    {
        return $this->gateways;
    }
}
