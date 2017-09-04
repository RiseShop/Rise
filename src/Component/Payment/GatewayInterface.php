<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Payment;

/**
 * Interface GatewayInterface.
 */
interface GatewayInterface
{
    /**
     * @param bool $testMode
     */
    public function setTestMode($testMode);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param PurchaseParametersInterface $parameters
     *
     * @return ResponseInterface
     */
    public function purchase(PurchaseParametersInterface $parameters);

    /**
     * @return bool
     */
    public function supportValidate();

    /**
     * @return bool
     */
    public function supportComplete();
}
