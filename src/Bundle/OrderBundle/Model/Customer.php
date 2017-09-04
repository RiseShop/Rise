<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\OrderBundle\Model;

use Mindy\Orm\Fields\CharField;
use Mindy\Orm\Fields\EmailField;
use Mindy\Orm\Fields\ForeignField;
use Mindy\Orm\Fields\TextField;
use Mindy\Orm\Model;
use Mindy\Orm\ModelInterface;
use Rise\Bundle\UserBundle\Model\User;

/**
 * Class Customer.
 *
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_name
 * @property string $email
 * @property string $phone
 * @property string $country
 * @property string $city
 * @property string $region
 * @property string $address
 * @property int|string $zip_code
 * @property ModelInterface $user
 */
class Customer extends Model implements CustomerInterface
{
    public static function getFields()
    {
        return [
            'phone' => [
                'class' => CharField::class,
                'verboseName' => 'Телефон'
            ],
            'email' => [
                'class' => EmailField::class,
                'verboseName' => 'Электронная почта'
            ],
            'first_name' => [
                'class' => CharField::class,
                'verboseName' => 'Имя'
            ],
            'last_name' => [
                'class' => CharField::class,
                'verboseName' => 'Фамилия'
            ],
            'middle_name' => [
                'class' => CharField::class,
                'verboseName' => 'Отчество'
            ],
            'country' => [
                'class' => CharField::class,
                'null' => true,
                'verboseName' => 'Страна'
            ],
            'region' => [
                'class' => CharField::class,
                'null' => true,
                'verboseName' => 'Регион'
            ],
            'city' => [
                'class' => CharField::class,
                'null' => true,
                'verboseName' => 'Город'
            ],
            'zip_code' => [
                'class' => CharField::class,
                'null' => true,
                'verboseName' => 'Индекс'
            ],
            'address' => [
                'class' => TextField::class,
                'null' => true,
                'verboseName' => 'Адрес'
            ],
            'user' => [
                'class' => ForeignField::class,
                'modelClass' => User::class,
                'verboseName' => 'Пользователь'
            ],
        ];
    }

    public function __toString()
    {
        return (string) sprintf('%s %s %s',
            $this->last_name,
            $this->middle_name,
            $this->first_name
        );
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zip_code;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middle_name;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return string|int
     */
    public function getId()
    {
        return $this->id;
    }
}
