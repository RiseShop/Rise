<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\OrderBundle\Form;

use Mindy\Bundle\AdminBundle\Form\Type\ButtonsType;
use Rise\Bundle\OrderBundle\Model\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('last_name', TextType::class, [
                'label' => 'Фамилия',
            ])
            ->add('first_name', TextType::class, [
                'label' => 'Имя',
            ])
            ->add('middle_name', TextType::class, [
                'label' => 'Отчество',
            ])
            ->add('country', TextType::class, [
                'label' => 'Страна',
            ])
            ->add('region', TextType::class, [
                'label' => 'Регион',
            ])
            ->add('city', TextType::class, [
                'label' => 'Город',
            ])
            ->add('zip_code', NumberType::class, [
                'label' => 'Почтовый индекс',
            ])
            ->add('address', TextareaType::class, [
                'label' => 'Адрес',
            ])
            ->add('phone', TextType::class, [
                'label' => 'Телефон',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Электронная почта',
            ])
            ->add('buttons', ButtonsType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
