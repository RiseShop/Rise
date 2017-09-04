<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DynamicFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $attributeGroup = $options['entity'];

        if ($attributeGroup) {
            foreach ($attributeGroup->attributes->all() as $attribute) {
                switch ($attribute->widget) {
                    case 'radio':
                    case 'select':
                    case 'checkbox':
                    default:
                        $builder->add($attribute->code, ChoiceType::class, [
                            'required' => false,
                            'label' => $attribute->name,
                            'choices' => $attribute->value->all(),
                            'choice_label' => 'value',
                            'choice_value' => 'id',
                        ]);
                        break;
                }
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'mapped' => false,
            'entity' => null,
        ]);
    }
}
