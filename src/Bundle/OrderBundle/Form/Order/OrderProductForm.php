<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\OrderBundle\Form\Order;

use Mindy\Bundle\AdminBundle\Form\Type\ButtonsType;
use Rise\Bundle\OrderBundle\Model\OrderProduct;
use Rise\Bundle\ProductBundle\Model\Variant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class OrderProductForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('variant', ChoiceType::class, [
                'label' => 'Вариант',
                'choices' => Variant::objects()->all(),
                'choice_label' => 'name',
                'choice_value' => 'id',
            ])
            ->add('quantity', TextType::class, [
                'label' => 'Количество',
                'required' => false,
                'data' => 1,
                'constraints' => [
                    new Assert\Range(['min' => 0, 'max' => 1000000]),
                ],
            ])
            ->add('discount', TextType::class, [
                'label' => 'Сумма скидки',
                'data' => 0,
                'constraints' => [
                    new Assert\Range(['min' => 0, 'max' => 100]),
                ],
                'required' => false,
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Комментарий',
                'required' => false,
            ])
            ->add('buttons', ButtonsType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderProduct::class,
        ]);
    }
}
