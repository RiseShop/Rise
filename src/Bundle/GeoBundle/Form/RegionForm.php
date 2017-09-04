<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\GeoBundle\Form;

use Mindy\Bundle\AdminBundle\Form\Type\ButtonsType;
use Rise\Bundle\GeoBundle\Model\Region;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('continent', TextType::class, [
                'label' => 'Континент',
                'attr' => [
                    'length' => 2,
                ],
            ])
            ->add('name_ru', TextType::class, [
                'label' => 'Название RU',
                'attr' => [
                    'length' => 128,
                ],
            ])
            ->add('name_en', TextType::class, [
                'label' => 'Название EN',
                'attr' => [
                    'length' => 128,
                ],
            ])
            ->add('lat', TextType::class, [
                'label' => 'Широта',
                'attr' => [
                    'length' => 15,
                ],
            ])
            ->add('lon', TextType::class, [
                'label' => 'Долгота',
                'attr' => [
                    'length' => 15,
                ],
            ])
            ->add('timezone', TextType::class, [
                'label' => 'Временная зона',
                'attr' => [
                    'length' => 30,
                ],
            ])
            ->add('buttons', ButtonsType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Region::class,
        ]);
    }
}
