<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\OrderBundle\Form\Wizard;

use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Topaz\Bundle\UserBundle\Model\User;

class Step1FormType extends AbstractType
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * Step1FormType constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return UserInterface|null
     */
    protected function getUser()
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return;
        }

        return $user;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->getUser();

        $builder
            ->add('last_name', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Фамилия'],
            ])
            ->add('first_name', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Имя'],
            ])
            ->add('middle_name', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Отчество'],
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Электронная почта'],
                'constraints' => [
                    new Assert\Callback(function ($value, ExecutionContextInterface $context, $payload) use ($user) {
                        if (
                            $user === null &&
                            User::objects()->filter(['email' => $value])->count() > 0
                        ) {
                            $context
                                ->buildViolation('Данный электронный адрес уже используется на сайте. Пожалуйста авторизуйтесь перед оформлением заказа.')
                                ->atPath('email')
                                ->addViolation();
                        }
                    }),
                ],
            ])
            ->add('phone', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Телефон'],
                'constraints' => [
                    new PhoneNumber([
                        'defaultRegion' => 'RU'
                    ]),
                    new Assert\Callback(function ($value, ExecutionContextInterface $context, $payload) use ($user) {
                        if (
                            $user === null &&
                            User::objects()->filter(['phone' => $value])->count() > 0
                        ) {
                            $context
                                ->buildViolation('Данный номер телефона уже используется на сайте. Пожалуйста авторизуйтесь перед оформлением заказа.')
                                ->atPath('phone')
                                ->addViolation();
                        }
                    }),
                ],
            ])
            ->add('next', SubmitType::class, [
                'label' => 'Далее',
                'attr' => ['class' => 'b-button b-button_red'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
