<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Delivery\Service;

use Rise\Component\Delivery\DimensionsInterface;
use Rise\Component\Delivery\LocationInterface;
use Rise\Component\Delivery\ParametersInterface;
use Rise\Component\Delivery\Result;
use Rise\Component\Delivery\UnknownLocationException;
use SoapClient;

class DpdService implements DeliveryServiceInterface
{
    use CachePoolAwareTrait;

    const CACHE_KEY = 'delivery.service.dpd';

    /**
     * @var string
     */
    protected $apiToken;
    /**
     * @var string
     */
    protected $password;
    /**
     * @var bool
     */
    protected $test = false;
    /**
     * @var array
     */
    protected $classifications = [];
    /**
     * @var array
     */
    private $geoData = [];

    /**
     * DpdService constructor.
     *
     * @param $apiToken
     * @param $password
     * @param bool $test
     */
    public function __construct($apiToken, $password, $test = false)
    {
        $this->apiToken = $apiToken;
        $this->password = $password;
        $this->test = $test;
    }

    protected function request($service, $method, array $parameters)
    {
        try {
            return (array) call_user_func([$this->getClient($service), $method], [
                'request' => array_merge($parameters, [
                    'auth' => [
                        'clientNumber' => $this->apiToken,
                        'clientKey' => $this->password,
                    ],
                ]),
            ]);
        } catch (\Exception $e) {
            if (mb_strpos($e->getMessage(), 'Превышен лимит', 0, 'UTF-8') === 0) {
                throw new RateLimitException($e->getMessage());
            }
            throw $e;
        }
    }

    /**
     * @param $obj
     *
     * @return array|string
     */
    protected function toArray($obj)
    {
        if (is_object($obj) || is_array($obj)) {
            $arr = [];
            for (reset($obj); list($k, $v) = each($obj);) {
                if ($k === 'GLOBALS') {
                    continue;
                }
                $arr[$k] = $this->toArray($v);
            }

            return $arr;
        } elseif (gettype($obj) == 'boolean') {
            return $obj ? 'true' : 'false';
        }

        return $obj;
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    public function getCitiesCashPay(array $parameters = [])
    {
        $callback = function () use ($parameters) {
            $response = $this->request('geography2', 'getCitiesCashPay', $parameters);

            return $this->toArray($response);
        };

        if (empty($this->geoData)) {
            $cachePool = $this->getCacheItemPool();
            if ($cachePool) {
                $cacheItem = $cachePool->getItem(self::CACHE_KEY);
                if ($cacheItem->isHit()) {
                    return $cacheItem->get();
                }
                $this->geoData = $callback->__invoke();
                $cacheItem->set($this->geoData);

                $cachePool->save($cacheItem);

                return $this->geoData;
            }
            $this->geoData = $callback->__invoke();

            return $this->geoData;
        }

        return $this->geoData;
    }

    /**
     * @param $service
     *
     * @return SoapClient
     */
    protected function getClient($service)
    {
        return new SoapClient(sprintf(
            'http://ws%s.dpd.ru/services/%s?wsdl',
            $this->test ? 'test' : '', $service
        ));
    }

    /**
     * @param $name
     *
     * @return null|string
     */
    public function findCountryId($name)
    {
        $shortCountry = mb_strtolower(mb_substr($name, 0, 5, 'UTF-8'), 'UTF-8');
        foreach ($this->getClassifications() as $country => $data) {
            if (mb_strpos(mb_strtolower($country, 'UTF-8'), $shortCountry, 0, 'UTF-8') !== false) {
                return $data['code'];
            }
        }
    }

    /**
     * @param $country
     * @param $region
     *
     * @return null|string
     */
    public function findRegionId($country, $region)
    {
        $shortCountry = mb_strtolower(mb_substr($country, 0, 5, 'UTF-8'), 'UTF-8');
        $shortRegion = explode(' ', mb_strtolower($region, 'UTF-8'))[0];

        foreach ($this->getClassifications() as $country => $data) {
            if (mb_strpos(mb_strtolower($country, 'UTF-8'), $shortCountry, 0, 'UTF-8') !== false) {
                foreach ($data['regions'] as $region => $regionData) {
                    if (mb_strpos(mb_strtolower($region, 'UTF-8'), $shortRegion, 0, 'UTF-8') !== false) {
                        return $regionData['code'];
                    }
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function findCityId($country, $region, $city)
    {
        $shortCountry = mb_strtolower(mb_substr($country, 0, 5, 'UTF-8'), 'UTF-8');
        $shortRegion = explode(' ', mb_strtolower($region, 'UTF-8'))[0];

        foreach ($this->getClassifications() as $country => $data) {
            if (mb_strpos(mb_strtolower($country, 'UTF-8'), $shortCountry, 0, 'UTF-8') !== false) {
                foreach ($data['regions'] as $region => $regionData) {
                    if (mb_strpos(mb_strtolower($region, 'UTF-8'), $shortRegion, 0, 'UTF-8') !== false) {
                        foreach ($regionData['cities'] as $name => $code) {
                            if (mb_strtolower($name, 'UTF-8') == mb_strtolower($city, 'UTF-8')) {
                                return $code;
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPrice(LocationInterface $from, LocationInterface $to, DimensionsInterface $dimensions, ParametersInterface $parameters = null)
    {
        $fromCityId = $this->findCityId($from->getCountry(), $from->getRegion(), $from->getCity());
        if (null === $fromCityId) {
            throw new UnknownLocationException();
        }

        $toCityId = $this->findCityId($to->getCountry(), $to->getRegion(), $to->getCity());
        if (null === $toCityId) {
            throw new UnknownLocationException();
        }

        if ($this->findCountryId($to->getCountry() != $this->findCountryId($from->getCountry()))) {
            // Международная доставка
            $method = 'getServiceCostInternational';
        } else {
            // Доставка по Российской Федерации
            $method = 'getServiceCost2';
        }

        $response = $this->request('calculator2', $method, [
            'selfPickup' => $parameters->isSelfPickup(),
            'selfDelivery' => $parameters->isSelfDelivery(),

            // dpd принимает вес в килограммах, поэтому делим на 10
            'weight' => $dimensions->getWeight() / 10,

            'length' => $dimensions->getLength(),
            'height' => $dimensions->getHeight(),
            'width' => $dimensions->getWidth(),

            'delivery' => [
                'cityId' => $fromCityId,
            ],

            'pickup' => [
                // dpd принимает только ID, так как города Киров, к примеру, 2 в РФ
                'cityId' => $toCityId,
            ],
        ]);

        $arrayResponse = $this->toArray($response);
        $temp = array_shift($arrayResponse);
        $array = end($temp);

        $result = new Result();
        $result->setDays($array['days']);
        $result->setPrice($array['cost']);

        return $result;
    }

    public function setClassifications(array $classifications)
    {
        $this->classifications = $classifications;
    }

    public function getClassifications()
    {
        if (empty($this->classifications)) {
            $data = json_decode(file_get_contents(__DIR__.'/../data/dpd.json'), true);
            $this->setClassifications($data);
        }

        return $this->classifications;
    }

    public function createClassificationsFrom($src, $dst)
    {
        $csv = array_map(function ($line) {
            $decoded = iconv('cp1251', 'UTF-8', $line);

            return array_map(function ($part) {
                return str_getcsv($part, ';');
            }, str_getcsv($decoded, "\r\n"));
        }, file($src));

        $data = [];
        foreach (array_shift($csv) as $i => $line) {
            if ($i == 0 || count($line) != 6) {
                continue;
            }

            list($id, $code, $type, $city, $region, $country) = $line;
            if (!isset($data[$country])) {
                $data[$country] = [
                    'regions' => [],
                    'code' => substr($code, 0, 2),
                ];
            }

            if (!isset($data[$country]['regions'][$region])) {
                $data[$country]['regions'][$region] = [
                    'code' => substr($code, 2),
                    'cities' => [],
                ];
            }

            if (!isset($data[$country]['regions'][$region]['cities'][$city])) {
                $data[$country]['regions'][$region]['cities'][$city] = $id;
            }
        }
        file_put_contents($dst, json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    /**
     * @param string $country
     * @param string $region
     * @param string $city
     *
     * @return bool
     */
    public function support($country, $region, $city)
    {
        return $this->findCityId($country, $region, $city) !== null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Служба доставки DPD';
    }
}
