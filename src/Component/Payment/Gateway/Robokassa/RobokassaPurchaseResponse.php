<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Modules\Payment\Gateway\Robokassa;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class RobokassaPurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function getEndpoint()
    {
        if ($this->getRequest()->getTestMode()) {
            $url = 'http://test.robokassa.ru/Index.aspx';
        } else {
            $url = 'https://merchant.roboxchange.com/Index.aspx';
        }

        if ($this->getRedirectMethod() == 'GET') {
            return $url.'?'.http_build_query($this->getRedirectData());
        }

        return $url;
    }

    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return true;
    }

    public function getRedirectUrl()
    {
        return $this->getEndpoint();
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    public function getRedirectData()
    {
        return $this->data;
    }
}
