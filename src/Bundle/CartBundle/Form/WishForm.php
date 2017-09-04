<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\ShopBundle\Form;

use Mindy\Bundle\AdminBundle\Form\Type\ButtonsType;
use Rise\Bundle\ProductBundle\Model\Variant;
use Rise\Bundle\UserBundle\Model\User;
use Rise\Bundle\WishBundle\Model\Wish;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WishForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('variant', ChoiceType::class, [
                'label' => 'Продукт',
                'choices' => Variant::objects()->all(),
                'choice_label' => function ($variant) {
                    return (string) $variant;
                },
            ])
            ->add('user', ChoiceType::class, [
                'label' => 'Пользователь',
                'choices' => User::objects()->all(),
                'choice_label' => function ($user) {
                    return (string) $user;
                },
            ])
            ->add('buttons', ButtonsType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
        ]);
    }
}
