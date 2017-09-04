<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\PaymentBundle\Form;

use Mindy\Bundle\AdminBundle\Form\Type\ButtonsType;
use Rise\Bundle\PaymentBundle\Model\Payment;
use Rise\Component\Payment\Factory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentForm extends AbstractType
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * PaymentForm constructor.
     *
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = [];
        foreach ($this->factory->getGateways() as $id => $gateway) {
            $choices[$gateway->getName()] = $id;
        }

        $builder
            ->add('name', TextType::class, [
                'label' => 'Название',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Описание',
            ])
            ->add('is_enabled', CheckboxType::class, [
                'label' => 'Включено',
                'empty_data' => true,
            ])
            ->add('gateway', ChoiceType::class, [
                'label' => 'Платежный шлюз',
                'choices' => $choices,
            ])
            ->add('buttons', ButtonsType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
        ]);
    }
}
