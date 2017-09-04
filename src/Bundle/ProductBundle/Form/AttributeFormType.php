<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\ProductBundle\Form;

use Rise\Bundle\ProductBundle\Model\Attribute;
use Rise\Bundle\ProductBundle\Model\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttributeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $attribute = $builder->getData();

        $builder
            ->add('name', TextType::class, [
                'label' => 'Название',
            ])
            ->add('code', TextType::class, [
                'label' => 'Код',
            ])
            ->add('entities', ChoiceType::class, [
                'label' => 'Класс товаров',
                'expanded' => true,
                'multiple' => true,
                'choices' => Entity::objects()->all(),
                'choice_label' => 'name',
                'choice_value' => 'id',
            ])
//            ->add('value', CollectionType::class, [
//                'label' => 'Значения',
//                'entry_options' => ['label' => false],
//                'entry_type' => TextType::class,
//                'allow_delete' => true,
//                'allow_add' => true,
//            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Сохранить',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Attribute::class,
        ]);
    }
}
