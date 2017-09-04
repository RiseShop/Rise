<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Delivery\Service\Ems;

use GuzzleHttp\Client;
use Rise\Component\Delivery\DimensionsInterface;
use Rise\Component\Delivery\LocationInterface;
use Rise\Component\Delivery\ParametersInterface;
use Rise\Component\Delivery\Result;
use Rise\Component\Delivery\ResultInterface;
use Rise\Component\Delivery\Service\DeliveryServiceInterface;

/**
 * Class EmsService.
 */
class EmsService implements DeliveryServiceInterface
{
    /**
     * @var string
     */
    protected $endpoint;

    /**
     * EmsService constructor.
     *
     * @param string $endpoint
     */
    public function __construct($endpoint = 'http://emspost.ru/api/rest/')
    {
        $this->endpoint = $endpoint;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'EMS почта россии';
    }

    protected function fetchCityList()
    {
        $client = new Client();
        $response = $client->request('GET', $this->endpoint, [
            'query' => [
                // http://emspost.ru/api/rest/?method=ems.get.locations&type=cities&plain=true
                'method' => 'ems.get.locations',
                'type' => 'cities',
                'plain' => true,
            ],
        ]);

        if ($response->getStatusCode() === 200) {
            $json = json_decode($response->getBody(), true);
            $data = [];
            foreach ($json['rsp']['locations'] as $location) {
                $data[$location['value']] = mb_strtolower($location['name'], 'UTF-8');
            }

            return $data;
        }

        return [];
    }

    protected function fetchRegionList()
    {
        $client = new Client();
        $response = $client->request('GET', $this->endpoint, [
            'query' => [
                // http://emspost.ru/api/rest/?method=ems.get.locations&type=regions&plain=true
                'method' => 'ems.get.locations',
                'type' => 'regions',
                'plain' => true,
            ],
        ]);

        if ($response->getStatusCode() === 200) {
            $json = json_decode($response->getBody(), true);
            $data = [];
            foreach ($json['rsp']['locations'] as $location) {
                $data[$location['value']] = mb_strtolower($location['name'], 'UTF-8');
            }

            return $data;
        }

        return [];
    }

    /**
     * @param string $region
     *
     * @return null|string
     */
    protected function findRegion($region)
    {
        $shortRegion = explode(' ', mb_strtolower($region, 'UTF-8'))[0];
        foreach ($this->fetchRegionList() as $value => $name) {
            if (mb_strpos(mb_strtolower($name, 'UTF-8'), $shortRegion, 0, 'UTF-8') !== false) {
                return $value;
            }
        }
    }

    /**
     * @param string $city
     *
     * @return null|string
     */
    protected function findCity($city)
    {
        foreach ($this->fetchCityList() as $value => $name) {
            if (mb_strpos(mb_strtolower($name, 'UTF-8'), mb_strtolower($city, 'UTF-8'), 0, 'UTF-8') !== false) {
                return $value;
            }
        }
    }

    /**
     * @param LocationInterface   $from
     * @param LocationInterface   $to
     * @param DimensionsInterface $dimensions
     * @param ParametersInterface $parameters
     *
     * @return null|ResultInterface
     */
    public function getPrice(LocationInterface $from, LocationInterface $to, DimensionsInterface $dimensions, ParametersInterface $parameters = null)
    {
        $client = new Client();
        $res = $client->request('GET', $this->endpoint, [
            'query' => [
                'method' => 'ems.calculate',
                'from' => $this->findCity($from->getCity()),
                'to' => $this->findCity($to->getCity()),
                'weight' => $dimensions->getWeight(),
            ],
        ]);

        $result = new Result();

        if ($res->getStatusCode() === 200) {
            $json = json_decode($res->getBody(), true);
            $data = $json['rsp'];
            $term = $data['term'];
            $result->setDays(array_sum($term) / count($term));
            $result->setPrice($data['price']);
        }

        return $result;
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
        return $this->findRegion($region) && $this->findCity($city);
    }
}
