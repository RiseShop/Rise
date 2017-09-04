<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\GeoBundle\Geo;

use Rise\Bundle\GeoBundle\Model\City;
use Rise\Bundle\GeoBundle\Model\Country;
use Rise\Bundle\GeoBundle\SxGeo\SxGeo;

/**
 * Created by PhpStorm.
 * User: max
 * Date: 17/01/17
 * Time: 16:05.
 */
class Geo
{
    /**
     * @var City
     */
    protected $city;
    /**
     * @var Country
     */
    protected $country;
    /**
     * @var SxGeo
     */
    protected $sxGeo;

    /**
     * Geo constructor.
     */
    public function __construct()
    {
        $this->sxGeo = new SxGeo(__DIR__.'/../SxGeo/SxGeoCity.dat');
    }

    /**
     * @param $ip
     *
     * @return bool
     */
    public function detect($ip)
    {
        $countryRaw = $this->sxGeo->getCountry($ip);
        $cityRaw = $this->sxGeo->getCity($ip);

        if (empty($cityRaw['id']) && empty($countryRaw['id'])) {
            return false;
        }

        if (!empty($cityRaw)) {
            /** @var City $city */
            $city = City::objects()->get([
                'id' => $cityRaw['id'],
            ]);
            $this->setCity($city);
        }

        if (!empty($countryRaw)) {
            /** @var Country $country */
            $country = Country::objects()->get([
                'id' => $countryRaw['id'],
            ]);
            $this->setCountry($country);
        }

        return true;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param Country $country
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;
    }

    /**
     * @return City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param City $city
     */
    public function setCity(City $city)
    {
        $this->city = $city;
    }
}
