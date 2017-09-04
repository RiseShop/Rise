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

class RobokassaCompletePurchaseResponse extends AbstractResponse
{
    /**
     * Is the response successful?
     *
     * @return bool
     */
    public function isSuccessful()
    {
        // TODO: Implement isSuccessful() method.
    }
}
