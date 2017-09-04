<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\OrderBundle\Form\Wizard;

use Rise\Bundle\PaymentBundle\Model\Payment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class Step2FormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('payment', ChoiceType::class, [
                'label' => 'Способ оплаты',
                'required' => false,
                'placeholder' => false,
                'expanded' => true,
                'choices' => Payment::objects()->enabled()->all(),
                'choice_label' => 'name',
                'choice_value' => 'id',
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('prev', SubmitType::class, [
                'label' => 'Назад',
                'validation_groups' => false,
                'attr' => ['class' => 'b-button'],
            ])
            ->add('next', SubmitType::class, [
                'label' => 'Далее',
                'attr' => ['class' => 'b-button b-button_red'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
