<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Payment\Gateway\Sberbank;

use Rise\Component\Payment\AbstractResponse;
use Rise\Component\Payment\OrderNumberInterface;

class SberbankPurchaseResponse extends AbstractResponse implements OrderNumberInterface
{
    /**
     * @return bool
     */
    public function isSuccess()
    {
        return false;
    }

    public function getOrderNumber()
    {
        $parameters = json_decode($this->response->getBody(), true);

        return $parameters['orderId'];
    }

    /**
     * @return bool
     */
    public function isRedirect()
    {
        if ($this->response->getStatusCode() === 200) {
            $parameters = json_decode($this->response->getBody(), true);
            if (isset($parameters['orderId'], $parameters['formUrl'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isCancelled()
    {
        // TODO: Implement isCancelled() method.
    }

    /**
     * @return string
     */
    public function getError()
    {
        return (string) $this->response->getBody();
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        $json = json_decode($this->response->getBody(), true);

        return $json['formUrl'];
    }
}
