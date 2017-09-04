<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Payment;

abstract class AbstractFormResponse implements FormResponseInterface
{
    use ParametersAwareTrait;

    /**
     * @var bool
     */
    protected $testMode;

    /**
     * AbstractFormResponse constructor.
     *
     * @param array $parameters
     * @param bool  $testMode
     */
    public function __construct(array $parameters = [], $testMode = false)
    {
        $this->setParameters($parameters);
        $this->testMode = $testMode;
    }
}
