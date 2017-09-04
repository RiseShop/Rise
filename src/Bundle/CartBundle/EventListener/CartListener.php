<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\CartBundle\EventListener;

use Mindy\Orm\ModelInterface;
use Rise\Bundle\CartBundle\Model\Cart;
use Rise\Component\Cart\CartInterface;
use Rise\Component\Cart\CartStorageInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class CartListener
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;
    /**
     * @var CartInterface
     */
    protected $cart;

    /**
     * CartListener constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param CartInterface         $cart
     */
    public function __construct(TokenStorageInterface $tokenStorage, CartInterface $cart)
    {
        $this->tokenStorage = $tokenStorage;
        $this->cart = $cart;
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

    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();
        $session = $request->getSession();

        if (null === $session) {
            return;
        }

        if (!$session->isStarted()) {
            $session->start();
        }

        list($cart, $created) = Cart::objects()->getOrCreate([
            'session_id' => $session->getId(),
            'is_active' => true,
        ]);

        /* @var Cart|CartStorageInterface $cart */
        $user = $this->getUser();
        if ($user instanceof ModelInterface) {
            if ($created || ($cart->user_id != $user->id)) {
                // Attach user
                $cart->user = $user;
                $cart->save();
            }
        }

        $this->cart->setStorage($cart);
    }
}
