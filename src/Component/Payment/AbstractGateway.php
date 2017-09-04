<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Payment;

use GuzzleHttp\Client;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractGateway implements GatewayInterface
{
    use TestModeTrait;
    use ParametersAwareTrait;
    use LoggerAwareTrait;

    /**
     * @var OptionsResolver
     */
    protected $optionsResolver;
    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * AbstractGateway constructor.
     *
     * @param array $parameters
     */
    public function __construct($parameters = [])
    {
        if (false === is_array($parameters)) {
            $parameters = [];
        }
        $this->httpClient = $this->getDefaultHttpClient();
        $this->setParameters($parameters);

        $this->optionsResolver = new OptionsResolver();
        $this->configureOptions($this->optionsResolver);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }

    protected function getDefaultHttpClient()
    {
        return new Client();
    }

    protected function formatAmount($amount)
    {
        return number_format($amount, 2, '.', '');
    }

    /**
     * @return bool
     */
    public function supportValidate()
    {
        return method_exists($this, 'validate');
    }

    /**
     * @return bool
     */
    public function supportComplete()
    {
        return method_exists($this, 'complete');
    }
}
