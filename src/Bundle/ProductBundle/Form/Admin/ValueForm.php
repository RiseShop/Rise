<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\ProductBundle\Form\Admin;

use Mindy\Bundle\AdminBundle\Form\Type\ButtonsType;
use Rise\Bundle\ProductBundle\Model\Attribute;
use Rise\Bundle\ProductBundle\Model\Value;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ValueForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('attr', ChoiceType::class, [
                'label' => 'Аттрибут',
                'choices' => Attribute::objects()->all(),
                'choice_value' => 'id',
                'choice_label' => 'name',
            ])
            ->add('value', TextType::class, [
                'label' => 'Значение',
            ])
            ->add('buttons', ButtonsType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Value::class,
        ]);
    }
}
