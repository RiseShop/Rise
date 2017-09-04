<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\CartBundle\Library;

use Mindy\Template\Library;
use Rise\Bundle\CartBundle\Model\Wish;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class WishLibrary extends Library
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * WishLibrary constructor.
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

    /**
     * @return array
     */
    public function getHelpers()
    {
        return [
            'wish_count' => function () {
                $user = $this->getUser();
                if (null === $user) {
                    return 0;
                }

                return Wish::objects()->filter([
                    'user' => $user,
                ])->count();
            },
        ];
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return [];
    }
}
