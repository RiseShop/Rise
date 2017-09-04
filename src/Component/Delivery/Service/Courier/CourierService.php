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
use Rise\Component\Delivery\ResultInterface;

class CourierService implements DeliveryServiceInterface
{
    /**
     * todo: для информации - что алматы и астана это казахстан а не россия,
     * todo: а проверка идет именно по россии. Остальные даже проверять не стал.
     *
     * @return array
     */
    protected function tariff()
    {
        return [
            377 => [
                'Ижевск', 'Йошкар-Ола', 'Казань', 'Набережные Челны', 'Нижнекамск',
                'Оренбург', 'Орск', 'Пенза', 'Самара', 'Саранск', 'Саратов', 'Стерлитамак',
                'Сызрань', 'Тольятти', 'Ульяновск', 'Уфа', 'Чебоксары', 'Энгельск', 'Москва',
                'Архангельск', 'Астрахань', 'Белгород', 'Брянск', 'Великий Новгород',
                'Владимир', 'Волгоград', 'Волгодонск', 'Волжский', 'Вологда', 'Воронеж',
                'Выборг', 'Геленджик', 'Дзержинск', 'Егоревьск', 'Ессентуки', 'Зеленоград',
                'Иваново', 'Калининград', 'Калуга', 'Кисловодск', 'Климовск', 'Клин',
                'Кострома', 'Красногорск', 'Краснодар', 'Курск', 'Липецк', 'Люберцы',
                'Магнитогорск', 'Мурманск', 'Мытищи', 'Невинномыск', 'Нижний Новгород',
                'Новороссийск', 'Новочеркасск', 'Ногинск', 'Обнинск', 'Орёл', 'Петрозаводск',
                'Подольск', 'Псков', 'Пятигорск', 'Ростов-на- Дону', 'Рыбинск', 'Рязань',
                'Севастополь', 'Северодвинск', 'Симферополь', 'Серпухов', 'Смоленск', 'Сочи',
                'Ставрополь', 'Старый Оскол', 'Сыктывкар', 'Таганрог', 'Тамбов', 'Тверь',
                'Туапсе', 'Тула', 'Тюмень', 'Ухта', 'Химки', 'Череповец', 'Черкесск',
                'Шахты', 'Элиста', 'Ярославль',
            ],
            590 => [
                'Усть-Каменогорск', 'Шымкент', 'Актау', 'Актобе', 'Алматы',
                'Ангарск', 'Астана', 'Атырау',
                'Караганда', 'Когалым', 'Костанай', 'Кызылорда',
            ],
            531 => [
                'Санкт-Петербург', 'Балашиха',
            ],
            438 => [
                'Барнаул', 'Бийск', 'Горно-Алтайск', 'Кемерово',
                'Красноярск', 'Железногорск', 'Минусинск', 'Новокузнецк',
                'Новосибирск', 'Омск', 'Рубцовск', 'Томск', 'Екатеринбург',
                'Нижний Тагил', 'Ханты-Мансийск', 'Чита', 'Комсомольск-на-Амуре',
                'Новый Уренгой', 'Ноябрьск',
            ],
            472 => [
                'Абакан', 'Кызыл', 'Северск', 'Улан-Удэ',
            ],
            484 => [
                'Уссурийск', 'Хабаровск', 'Братск', 'Владивосток', 'Иркутск',
                'Находка', 'Нерюнгри', 'Петропавловск-Камчатский', 'Южно-Сахалинск', 'Якутск',
            ],
            401 => [
                'Владикавказ', 'Курган', 'Махачкала', 'Миас', 'Нальчик',
                'Нижневартовск', 'Пермь', 'Тобольск', 'Челябинск', 'Сургут', 'Уральск',
            ],
            402 => ['Березники'],
        ];
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
        $result = new Result();

        $prices = $this->tariff();
        foreach ($prices as $price => $cities) {
            foreach ($cities as $city) {
                $cityLower = mb_strtolower($city, 'UTF-8');
                if (mb_strtolower($to->getCity(), 'UTF-8') == $cityLower) {
                    $result->setPrice($price);

                    return $result;
                }
            }
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
        return true;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Доставка курьером';
    }
}
