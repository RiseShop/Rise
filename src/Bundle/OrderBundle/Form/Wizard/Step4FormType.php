<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\OrderBundle\Form\Wizard;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Step4FormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment', TextareaType::class, [
                'label' => 'Комментарий',
                'required' => false,
            ])
            ->add('prev', SubmitType::class, [
                'label' => 'Назад',
                'validation_groups' => false,
                'attr' => ['class' => 'b-button'],
            ])
            ->add('finish', SubmitType::class, [
                'label' => 'Оформить заказ',
                'attr' => ['class' => 'b-button b-button_red'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
