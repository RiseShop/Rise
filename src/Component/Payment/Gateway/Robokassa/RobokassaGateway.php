<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Modules\Payment\Gateway\Robokassa;

use Mindy\Base\Mindy;
use Omnipay\Common\AbstractGateway;

/**
 * @method \Omnipay\Common\Message\ResponseInterface authorize(array $options = array())
 * @method \Omnipay\Common\Message\ResponseInterface completeAuthorize(array $options = array())
 * @method \Omnipay\Common\Message\ResponseInterface capture(array $options = array())
 * @method \Omnipay\Common\Message\ResponseInterface refund(array $options = array())
 * @method \Omnipay\Common\Message\ResponseInterface void(array $options = array())
 * @method \Omnipay\Common\Message\ResponseInterface createCard(array $options = array())
 * @method \Omnipay\Common\Message\ResponseInterface updateCard(array $options = array())
 * @method \Omnipay\Common\Message\ResponseInterface deleteCard(array $options = array())
 */
class RobokassaGateway extends AbstractGateway
{
    use RobokassaTrait;

    public function getName()
    {
        return 'Robokassa';
    }

    public function getDefaultParameters()
    {
        return [
            'MerchantLogin' => '',
            'password1' => '',
            'password2' => '',
            'OutSum' => '',
            'InvId' => '',
            'Desc' => '',
            'Email' => '',
            'SignatureValue' => '',
            'Shp_item' => 1,
            'Culture' => Mindy::app()->locale['language'],
        ];
    }

    public function purchase(array $parameters = [])
    {
        return $this->createRequest('\Modules\Payment\Gateway\Robokassa\RobokassaPurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest('\Modules\Payment\Gateway\Robokassa\RobokassaCompletePurchaseRequest', $parameters);
    }
}
