<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Payment;

class OfflinePurchaseResponse implements ResponseInterface
{
    /**
     * @return bool
     */
    public function isSuccess()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isRedirect()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isCancelled()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return null;
    }
}
