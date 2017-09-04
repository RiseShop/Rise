<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\ProductBundle\Form;

use Rise\Bundle\ProductBundle\Model\Category;
use Rise\Bundle\ProductBundle\Model\Entity;
use Rise\Bundle\ProductBundle\Model\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Название',
            ])
            ->add('entity', ChoiceType::class, [
                'label' => 'Тип товара',
                'required' => false,
                'choices' => Entity::objects()->all(),
                'choice_value' => 'id',
                'choice_label' => 'name',
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Категория',
                'expanded' => false,
                'multiple' => true,
                'choice_label' => 'name',
                'choice_value' => 'id',
                'choices' => Category::objects()->all(),
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Сохранить',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
