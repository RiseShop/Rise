<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Payment\Gateway\Yandex;

use Rise\Component\Payment\AbstractFormResponse;
use Rise\Component\Payment\FormBuilder;

class YandexPurchaseResponse extends AbstractFormResponse
{
    /**
     * @return string
     */
    public function getEndpoint()
    {
        if ($this->testMode) {
            return 'https://demomoney.yandex.ru/eshop.xml';
        }

        return 'http://money.yandex.ru/eshop.xml';
    }

    /**
     * @return string
     */
    public function buildForm()
    {
        $builder = new FormBuilder();

        return $builder->build($this->getEndpoint(), $this->getParameters());
    }
}
