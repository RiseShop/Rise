<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\ShopBundle\Form;

use Mindy\Bundle\ShopBundle\Model\Order\Customer;
use Mindy\Bundle\ShopBundle\Model\Order\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderCustomerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $instance = $builder->getData();

        $builder
            ->add('customer', ChoiceType::class, [
                'label' => 'Клиент',
                'choices' => Customer::objects()->all(),
                'choice_label' => function ($customer) {
                    return (string) $customer;
                },
                'preferred_choices' => function ($customer) use ($instance) {
                    return $customer->id == $instance->customer_id;
                },
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Сохранить',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
