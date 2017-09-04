<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\DeliveryBundle\Form\Type;

use Rise\Bundle\DeliveryBundle\Model\Delivery;
use Rise\Bundle\PaymentBundle\Model\Payment;
use Rise\Component\Delivery\Factory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeliveryType extends AbstractType
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * DeliveryType constructor.
     *
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Payment $payment */
        $payment = $options['payment'];

        $methods = Delivery::objects()
            ->filter(['payment__id' => $payment->id])
            ->valuesList(['service'], true);

        $services = [];
        foreach ($this->factory->getServices() as $code => $service) {
            if (in_array($code, $methods)) {
                $services[] = ['name' => $service->getName(), 'code' => $code];
            }
        }

        $builder->add('delivery', ChoiceType::class, [
            'label' => 'Способ доставки',
            'choices' => $services,
            'choice_label' => 'name',
            'choice_value' => 'code',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'payment' => null,
        ]);
    }
}
