<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Payment\Gateway\Sberbank;

use Psr\Http\Message\ResponseInterface;
use Rise\Component\Payment\AbstractRequest;

class SberbankPurchase extends AbstractRequest
{
    /**
     * @return string
     */
    public function getEndpoint()
    {
        if ($this->testMode) {
            $url = 'https://3dsec.sberbank.ru/payment/rest/register.do';
        } else {
            $url = 'https://securepayments.sberbank.ru/payment/rest/register.do';
        }

        $credentials = http_build_query([
            'userName' => $this->parameterBag->get('userName'),
            'password' => $this->parameterBag->get('password'),
            'orderNumber' => $this->parameterBag->get('orderNumber'),
            'amount' => $this->parameterBag->get('amount'),
            'returnUrl' => $this->parameterBag->get('returnUrl'),
            'failUrl' => $this->parameterBag->get('failUrl'),
        ]);

        return $url.'?'.$credentials;
    }

    /**
     * @return ResponseInterface
     */
    public function send()
    {
        $parameters = $this->parameterBag->all();
        unset($parameters['username'], $parameters['password']);

        return $this
            ->getHttpClient()
            ->request('POST', $this->getEndpoint(), $parameters);
    }
}
