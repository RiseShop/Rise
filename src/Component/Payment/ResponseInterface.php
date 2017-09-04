<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Payment;

interface ResponseInterface
{
    /**
     * @return bool
     */
    public function isSuccess();

    /**
     * @return bool
     */
    public function isRedirect();

    /**
     * @return bool
     */
    public function isCancelled();

    /**
     * @return string
     */
    public function getError();

    /**
     * @return string
     */
    public function getRedirectUrl();
}
