<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\ShopBundle\Form;

use Mindy\Bundle\ShopBundle\Model\Order\OrderProduct;
use Mindy\Bundle\ShopBundle\Model\Product\Variant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('variant', ChoiceType::class, [
                'label' => 'Товар',
                'choices' => Variant::objects()->all(),
                'choice_label' => function ($product) {
                    return sprintf('%s - %s', $product->name, $product->sku);
                },
            ])
            ->add('quantity', NumberType::class, [
                'label' => 'Количество',
                'data' => 1,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Сохранить',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderProduct::class,
        ]);
    }
}
