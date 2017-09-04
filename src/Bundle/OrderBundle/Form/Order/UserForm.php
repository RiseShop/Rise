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
use Mindy\Orm\ModelInterface;
use Rise\Bundle\OrderBundle\Model\Order;
use Rise\Bundle\UserBundle\Model\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', ChoiceType::class, [
                'label' => 'Пользователь',
                'choices' => User::objects()->all(),
                'choice_label' => function (ModelInterface $user) {
                    return (string) $user;
                },
                'choice_value' => 'id',
            ])
            ->add('buttons', ButtonsType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
