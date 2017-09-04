<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\OrderBundle\Form;

use Rise\Bundle\OrderBundle\Model\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CommentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment', TextareaType::class, [
                'label' => 'Комментарий',
            ])
            ->add('file', FileType::class, [
                'label' => 'Файл',
                'required' => false,
                'constraints' => [
                    new Assert\File([
                        'mimeTypes' => [
                            // OpenDocument
                            'application/vnd.oasis.opendocument.text',
                            'application/vnd.oasis.opendocument.spreadsheet',
                            'application/vnd.oasis.opendocument.presentation',
                            'application/vnd.oasis.opendocument.graphics',

                            // Microsoft Word 2007 файлы
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            // Microsoft Word файлы
                            'application/msword',
                            // Microsoft Excel файлы
                            'application/vnd.ms-excel',
                            // Microsoft Excel 2007 файлы
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            // Microsoft Powerpoint файлы
                            'application/vnd.ms-powerpoint',
                            // Microsoft Powerpoint 2007 файлы
                            'application/vnd.openxmlformats-officedocument.presentationml.presentation',

                            // Images
                            'image/gif',
                            'image/jpeg',
                            'image/png',

                            // Test documents
                            'text/richtext',
                            'text/plain',
                        ],
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Отправить комментарий',
                'attr' => ['class' => 'b-button'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
