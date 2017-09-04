<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\DeliveryBundle\Form;

use Mindy\Bundle\AdminBundle\Form\Type\ButtonsType;
use Mindy\Bundle\FormBundle\Form\DataTransformer\QuerySetTransformer;
use Rise\Bundle\DeliveryBundle\Model\Delivery;
use Rise\Bundle\PaymentBundle\Model\Payment;
use Rise\Component\Delivery\Factory;
use Rise\Component\Delivery\Service\DeliveryServiceInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeliveryForm extends AbstractType
{
    protected $factory;

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $services = [];
        foreach ($this->factory->getServices() as $key => $value) {
            $services[sprintf("%s (%s)", $value->getName(), $key)] = $key;
        }

        $builder
            ->add('service', ChoiceType::class, [
                'label' => 'Способ доставки',
                'choices' => $services,
            ])
            ->add('payment', ChoiceType::class, [
                'label' => 'Работает для способов оплат',
                'choices' => Payment::objects()->all(),
                'choice_label' => '[name]',
                'choice_value' => '[id]',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('name', TextType::class, [
                'label' => 'Название способа доставки',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Описание способа доставки',
            ])
            ->add('is_enabled', CheckboxType::class, [
                'label' => 'Включено',
                'required' => false,
            ])
            ->add('buttons', ButtonsType::class);

        $builder->get('payment')->addModelTransformer(new QuerySetTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Delivery::class,
        ]);
    }
}
