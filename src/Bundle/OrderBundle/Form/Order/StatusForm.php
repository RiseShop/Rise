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
use Rise\Bundle\OrderBundle\Model\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatusForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $instance = $builder->getData();

        $statuses = Order::getStatusChoices();

        $currentStatusId = $instance->status_id;
        $temp = array_flip($statuses);
        unset($temp[$currentStatusId]);
        $statuses = array_flip($temp);

        $builder
            ->add('status_id', ChoiceType::class, [
                'label' => 'Статус',
                'choices' => $statuses,
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
