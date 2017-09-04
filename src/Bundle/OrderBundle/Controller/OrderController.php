<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\OrderBundle\Controller;

use Mindy\Bundle\MindyBundle\Controller\Controller;
use Rise\Bundle\OrderBundle\Form\CommentForm;
use Rise\Bundle\OrderBundle\Model\Comment;
use Rise\Bundle\OrderBundle\Model\Order;
use Rise\Bundle\OrderBundle\Model\OrderProduct;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class OrderController.
 */
class OrderController extends Controller
{
    public function listAction(Request $request, UserInterface $user)
    {
        $qs = Order::objects()
            ->filter(['user' => $user])
            ->order(['-id']);
        $pager = $this->createPagination($qs);

        return $this->render('rise/order/list.html', [
            'orders' => $pager->paginate(),
            'pager' => $pager->createView(),
        ]);
    }

    public function viewAction(Request $request, $id, $token)
    {
        $order = Order::objects()->get(['id' => $id, 'token' => $token]);
        if (null === $order) {
            throw new NotFoundHttpException();
        }

        $orderProducts = OrderProduct::objects()->filter(['order' => $order])->all();
        $customer = $order->customer;

        $pager = $this->createPagination($order->comments->order(['-id']));

        $comment = new Comment(['order' => $order, 'user' => $this->getUser()]);
        $form = $this->createForm(CommentForm::class, $comment, [
            'method' => 'POST',
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            if ($comment->save()) {
                return $this->redirect($request->getRequestUri());
            }
        }

        return $this->render('rise/order/view.html', [
            'order' => $order,
            'customer' => $customer,
            'orderProducts' => $orderProducts,
            'comments' => $pager->paginate(),
            'pager' => $pager->createView(),
            'form' => $form->createView(),
        ]);
    }
}
