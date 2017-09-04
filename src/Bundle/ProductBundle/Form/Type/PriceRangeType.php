<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\ProductBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Created by PhpStorm.
 * User: max
 * Date: 01/12/16
 * Time: 16:22
 */
class PriceRangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(0, TextType::class, [
                'required' => false,
                'label' => 'Цена от',
            ])
            ->add(1, TextType::class, [
                'required' => false,
                'label' => 'Цена до',
            ]);
    }
}
