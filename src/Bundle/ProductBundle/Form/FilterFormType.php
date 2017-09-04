<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\ProductBundle\Form;

use Rise\Bundle\ProductBundle\Form\Type\PriceRangeType;
use Rise\Bundle\ProductBundle\Model\Attribute;
use Rise\Bundle\ProductBundle\Model\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $attributeGroup = $options['attribute_group'];

        $builder
            ->add('price', PriceRangeType::class, [
                'label' => 'Стоимость'
            ])
            ->add('entity', ChoiceType::class, [
                'label' => 'Тип',
                'expanded' => true,
                'multiple' => true,
                'choices' => Entity::objects()->all(),
                'choice_label' => 'name',
                'choice_value' => 'id',
            ]);

        if ($attributeGroup) {
            $attributes = $attributeGroup->attributes->all();
        } else {
            $attributes = Attribute::objects()->all();
        }

        foreach ($attributes as $attribute) {
            switch ($attribute->widget) {
                case 'radio':
                case 'select':
                case 'checkbox':
                default:
                    $builder->add($attribute->code, ChoiceType::class, [
                        'required' => false,
                        'label' => $attribute->name,
                        'choices' => $attribute->value->order(['value'])->all(),
                        'choice_label' => 'value',
                        'choice_value' => 'id',
                    ]);
                    break;
            }
        }

        $builder
            ->add('submit', SubmitType::class, [
                'label' => 'Подобрать',
                'attr' => ['class' => 'b-button b-button_fluid b-button_red b-button_big'],
            ])
        ;
    }

    public function getBlockPrefix()
    {
        return 'filter';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false,
            'attribute_group' => null,
        ]);
    }
}
