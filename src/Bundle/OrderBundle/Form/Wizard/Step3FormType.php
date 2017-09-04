<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\OrderBundle\Form\Wizard;

use Rise\Bundle\DeliveryBundle\Model\Delivery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class Step3FormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Payment $payment */
        $payment = $options['payment'];

        $builder
            ->add('delivery', ChoiceType::class, [
                'label' => 'Способ доставки',
                'choices' => Delivery::objects()
                    ->enabled()
                    ->filter(['payment__id' => $payment->id]),
                'choice_label' => 'name',
                'choice_value' => 'id',
            ])
            ->add('country', TextType::class, [
                'label' => 'Страна',
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(['groups' => 'delivery']),
                ],
            ])
            ->add('region', TextType::class, [
                'label' => 'Регион',
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(['groups' => 'delivery']),
                ],
            ])
            ->add('city', TextType::class, [
                'label' => 'Город / Населенный пункт',
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(['groups' => 'delivery']),
                ],
            ])
            ->add('zip_code', TextType::class, [
                'label' => 'Почтовый индекс',
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(['groups' => 'delivery']),
                ],
            ])
            ->add('address', TextareaType::class, [
                'label' => 'Адрес',
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(['groups' => 'delivery']),
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
        $resolver->setDefaults([
            'payment' => null,
            'validation_groups' => function (FormInterface $form) {
                $data = $form->getData();

                if ($data['delivery'] == 'dummy') {
                    return ['default'];
                }

                return ['default', 'delivery'];
            },
        ]);
    }
}
