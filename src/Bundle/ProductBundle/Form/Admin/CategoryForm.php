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
use Rise\Bundle\ProductBundle\Model\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $instance = $builder->getData();

        $builder
            ->add('parent', ChoiceType::class, [
                'label' => 'Родительская категория',
                'required' => false,
                'choices' => Category::objects()->order(['root', 'lft'])->all(),
                'choice_label' => function ($page) {
                    return sprintf('%s %s', str_repeat('-', $page->level - 1), $page);
                },
                'choice_value' => 'id',
                'choice_attr' => function ($page) use ($instance) {
                    return $page->pk == $instance->pk ? ['disabled' => 'disabled'] : [];
                },
            ])
            ->add('name', TextType::class, [
                'label' => 'Название',
            ])
            ->add('buttons', ButtonsType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
