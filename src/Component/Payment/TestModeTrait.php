<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Payment;

trait TestModeTrait
{
    /**
     * @var bool
     */
    protected $testMode = false;

    /**
     * @param bool $testMode
     */
    public function setTestMode($testMode)
    {
        $this->testMode = $testMode;
    }

    /**
     * @return bool
     */
    public function getTestMode()
    {
        return $this->testMode;
    }
}
