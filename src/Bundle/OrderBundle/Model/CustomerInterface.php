<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\OrderBundle\Model;

interface CustomerInterface
{
    /**
     * @return string
     */
    public function getEmail();

    /**
     * @return string
     */
    public function getPhone();

    /**
     * @return string
     */
    public function getCountry();

    /**
     * @return string
     */
    public function getCity();

    /**
     * @return string
     */
    public function getRegion();

    /**
     * @return string
     */
    public function getZipCode();

    /**
     * @return string
     */
    public function getFirstName();

    /**
     * @return string
     */
    public function getLastName();

    /**
     * @return string
     */
    public function getMiddleName();

    /**
     * @return string
     */
    public function getAddress();

    /**
     * @return string|int
     */
    public function getId();
}
