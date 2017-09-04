<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Modules\Payment\Gateway\Robokassa;

trait RobokassaTrait
{
    public function setEmail($value)
    {
        return $this->setParameter('Email', $value);
    }

    public function getEmail()
    {
        return $this->getParameter('Email');
    }

    public function setSignatureValue($value)
    {
        return $this->setParameter('SignatureValue', $value);
    }

    public function getSignatureValue()
    {
        $signature = $this->getParameter('SignatureValue');
        if (empty($signature)) {
            $rawStr = strtr('{login}:{out_summ}:{inv_id}:{password1}', [
                '{login}' => $this->getMerchantLogin(),
                '{out_summ}' => $this->getAmount(),
                '{inv_id}' => $this->getInvId(),
                '{password1}' => $this->getPassword1(),
            ]);
            $signature = md5($rawStr);
        }

        $this->setSignatureValue($signature);

        return $this->getParameter('SignatureValue');
    }

    public function setInvId($value)
    {
        return $this->setParameter('InvId', $value);
    }

    public function getInvId()
    {
        return $this->getParameter('InvId');
    }

    public function setAmount($value)
    {
        return $this->setParameter('amount', $value);
    }

    public function getAmount()
    {
        return $this->getParameter('amount');
    }

    public function setCurrency($value)
    {
        return $this->setParameter('currency', $value);
    }

    public function getCurrency()
    {
        return $this->getParameter('currency');
    }

    public function setCulture($value)
    {
        return $this->setParameter('Culture', $value);
    }

    public function getCulture()
    {
        return $this->getParameter('Culture');
    }

    public function setMerchantLogin($value)
    {
        return $this->setParameter('MerchantLogin', $value);
    }

    public function getMerchantLogin()
    {
        return $this->getParameter('MerchantLogin');
    }

    public function setPassword1($value)
    {
        return $this->setParameter('password1', $value);
    }

    public function getPassword1()
    {
        return $this->getParameter('password1');
    }

    public function setPassword2($value)
    {
        return $this->setParameter('password2', $value);
    }

    public function getPassword2()
    {
        return $this->getParameter('password2');
    }
}
