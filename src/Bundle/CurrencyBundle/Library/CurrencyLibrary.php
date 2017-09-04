<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\CurrencyBundle\Library;

use Mindy\Template\Library;

class CurrencyLibrary extends Library
{
    /**
     * @return array
     */
    public function getHelpers()
    {
        return [
            'format_price' => function ($price) {
                return sprintf('%s руб.', number_format($price, 2, '.', ' '));
            },
        ];
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return [];
    }
}
