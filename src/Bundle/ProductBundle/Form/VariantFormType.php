<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\ProductBundle\Form;

use Mindy\Bundle\AdminBundle\Form\Type\ButtonsType;
use Rise\Bundle\ProductBundle\Model\Variant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class VariantFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $variable = $builder->getData();
        $product = $options['product'];

        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Название',
            ])
            ->add('description_short', TextareaType::class, [
                'required' => false,
                'label' => 'Краткое описание',
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Описание',
            ])
            ->add('price', NumberType::class, [
                'required' => true,
                'label' => 'Стоимость',
            ])
            ->add('slug', TextType::class, [
                'label' => 'Слаг',
                'constraints' => [
                    new Assert\Callback(function ($slug, ExecutionContextInterface $context) use ($variable) {
                        $qs = Variant::objects()
                            ->filter(['slug' => $slug]);

                        if (false == $variable->getIsNewRecord()) {
                            $qs->exclude(['id' => $variable->id]);
                        }

                        if ($qs->count() > 0) {
                            $context
                                ->buildViolation('Товар с данным артикулом уже есть в базе')
                                ->addViolation();
                        }
                    }),
                ],
            ])
            ->add('sku', TextType::class, [
                'label' => 'Артикул',
            ])
            ->add('quantity', NumberType::class, [
                'label' => 'Количество',
                'required' => false,
                'empty_data' => 1,
            ])
            ->add('weight', NumberType::class, [
                'label' => 'Вес',
                'required' => false,
                'empty_data' => 0,
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Статус',
                'choices' => array_flip(Variant::getStatusChoices()),
            ])
            ->add('available_for_sale', CheckboxType::class, [
                'label' => 'Доступно для продажи',
                'required' => false,
            ])
            ->add('inventory_tracking', CheckboxType::class, [
                'label' => 'Включить контроль остатков',
                'required' => false,
            ])
            ->add('is_master', CheckboxType::class, [
                'label' => 'Главный вариант товара',
                'required' => false,
            ]);

        if ($product && ($attributeGroup = $product->entity)) {
            $builder->add('eav', DynamicFormType::class, [
                'entity' => $product->entity,
            ]);
        }

        $builder
            ->add('buttons', ButtonsType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'product' => null,
            'data_class' => Variant::class,
        ]);
    }
}
