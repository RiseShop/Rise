<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Payment;

use Symfony\Component\HttpFoundation\ParameterBag;

trait ParametersAwareTrait
{
    /**
     * @var ParameterBag
     */
    protected $parametersBag;

    /**
     * @return ParameterBag
     */
    protected function getBag()
    {
        if (null === $this->parametersBag) {
            $this->parametersBag = new ParameterBag($this->getDefaultParameters());
        }

        return $this->parametersBag;
    }

    /**
     * @return array
     */
    protected function getDefaultParameters()
    {
        return [];
    }

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters)
    {
        foreach ($parameters as $key => $value) {
            $this->setParameter($key, $value);
        }
    }

    /**
     * @return array
     */
    protected function getParameters()
    {
        return $this->getBag()->all();
    }

    /**
     * @param $key
     * @param null $default
     *
     * @return mixed
     */
    protected function getParameter($key, $default = null)
    {
        return $this->getBag()->get($key, $default);
    }

    /**
     * @param $key
     * @param $value
     */
    protected function setParameter($key, $value)
    {
        $this->getBag()->set($key, $value);
    }
}
