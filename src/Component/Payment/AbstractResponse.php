<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Payment;

use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

abstract class AbstractResponse implements ResponseInterface
{
    protected $response;

    /**
     * AbstractResponse constructor.
     *
     * @param PsrResponseInterface $response
     */
    public function __construct(PsrResponseInterface $response)
    {
        $this->response = $response;
    }

    public function dump()
    {
        dump((string) $this->response->getBody());
        die;
    }

    /**
     * @return bool
     */
    abstract public function isSuccess();

    /**
     * @return bool
     */
    abstract public function isRedirect();

    /**
     * @return bool
     */
    abstract public function isCancelled();
}
