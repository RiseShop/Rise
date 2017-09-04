<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\OrderBundle\Form;

use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderQuickForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Ваше имя'],
            ])
            ->add('phone', PhoneNumberType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Телефон'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Оформить заказ',
                'attr' => ['class' => 'b-button'],
            ]);
    }
}
