<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\CartBundle\Controller;

use Mindy\Bundle\MindyBundle\Controller\Controller;
use Rise\Bundle\CartBundle\Model\Position;
use Rise\Bundle\OrderBundle\EventListener\OrderCreateEvent;
use Rise\Bundle\OrderBundle\Form\OrderQuickForm;
use Rise\Bundle\OrderBundle\Model\Customer;
use Rise\Bundle\OrderBundle\Model\Order;
use Rise\Bundle\OrderBundle\Model\OrderProduct;
use Rise\Bundle\ProductBundle\Model\Variant;
use Rise\Component\Cart\CartInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartController extends Controller
{
    public function listAction()
    {
        return $this->render('rise/cart/list.html', [
            'cart' => $this->getCart(),
        ]);
    }

    public function addAction(Request $request)
    {
        $id = $request->request->get('id');
        if (empty($id)) {
            throw new NotFoundHttpException();
        }

        $quantity = $request->request->get('quantity', 1);
        $params = $request->request->get('params', []);

        $variant = Variant::objects()->published()->get(['pk' => $id]);
        if ($variant === null) {
            return new JsonResponse([
                'status' => false,
                'error' => 'Product not found',
            ]);
        }

        $position = new Position([
            'variant' => $variant,
            'quantity' => $quantity,
            'params' => $params,
        ]);

        $cart = $this->getCart();
        if ($cart->hasPosition($position->generateUniqueId())) {
            $position = $cart->getPosition($position->generateUniqueId());
            $position->setQuantity($position->getQuantity() + 1);

            $cart->removePosition($position->generateUniqueId());
            $cart->addPosition($position->generateUniqueId(), $position);
        } else {
            $cart->addPosition($position->generateUniqueId(), $position);
        }

        if ($this->isAcceptJson($request)) {
            return $this->json([
                'status' => true,
                'cart' => $cart,
                'html' => $this->renderTemplate('rise/cart/list.html', [
                    'cart' => $cart,
                ]),
            ]);
        }

        return $this->forward([$this, 'listAction'], [
            'request' => $request,
        ], $request->query->all());
    }

    public function fastAction(Request $request, $slug)
    {
        $variant = Variant::objects()
            ->published()
            ->get(['slug' => $slug]);

        if ($variant === null) {
            throw new NotFoundHttpException();
        }

        $position = new Position([
            'variant' => $variant,
            'quantity' => 1,
        ]);

        $cart = $this->getCart();
        $cart->clear();
        $cart->addPosition($position->generateUniqueId(), $position);

        return $this->redirectToRoute('shop_order_wizard');
    }

    public function quantityAction(Request $request)
    {
        $index = $request->request->get('index');
        if (empty($index)) {
            throw new NotFoundHttpException();
        }

        $quantity = $request->request->getInt('quantity');
        if (empty($quantity)) {
            throw new NotFoundHttpException();
        }

        $cart = $this->getCart();
        if ($cart->hasPosition($index)) {
            $position = $cart->getPosition($index);
            $position->setQuantity($quantity);
        }

        if ($this->isAcceptJson($request)) {
            return $this->json([
                'status' => true,
                'cart' => $cart,
                'html' => $this->renderTemplate('rise/cart/list.html', [
                    'cart' => $cart,
                ]),
            ]);
        }

        return $this->forward([$this, 'listAction'], [
            'request' => $request,
        ], $request->query->all());
    }

    public function quickAction(Request $request)
    {
        $cart = $this->getCart();

        $form = $this->createForm(OrderQuickForm::class, [], [
            'action' => $this->generateUrl('rise_cart_quick'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $user = $this->getUser();

            // Create customer
            $customer = new Customer([
                'first_name' => $data['first_name'],
                'phone' => $data['phone']->getNationalNumber(),
                'user' => $user,
            ]);
            if (false == $customer->save()) {
                throw new RuntimeException('Failed to save customer');
            }

            $order = new Order([
                'status_id' => Order::STATUS_NEW,
                'user' => $user,
                'customer' => $customer,
            ]);

            if (false == $order->save()) {
                throw new RuntimeException('Failed to save order');
            }

            foreach ($this->getCart()->getPositions() as $position) {
                $variant = $position->getProduct();
                $params = [];
                foreach ($variant->values->all() as $value) {
                    $params[$value->attribute->name] = $value->value;
                }
                $orderProduct = new OrderProduct([
                    'order' => $order,
                    'name' => (string) $variant,
                    'variant' => $variant,
                    'params' => json_encode($params, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                    'quantity' => $position->getQuantity(),
                    'price' => $position->getPrice(),
                ]);
                $orderProduct->save();

                $this->get('event_dispatcher')->dispatch(
                    OrderCreateEvent::NAME,
                    new OrderCreateEvent($order)
                );

                return $this->redirectToRoute('shop_order_view', [
                    'id' => $order->id,
                    'token' => $order->token,
                ]);
            }
        }

        return $this->render('rise/order/quick.html', [
            'cart' => $cart,
            'form' => $form->createView(),
        ]);
    }

    public function removeAction(Request $request)
    {
        $key = $request->request->get('key');
        if (empty($key)) {
            throw new NotFoundHttpException();
        }

        $cart = $this->getCart();
        $cart->removePosition($key);

        if ($this->isAcceptJson($request)) {
            return $this->json([
                'status' => true,
                'cart' => $cart,
                'html' => $this->renderTemplate('rise/cart/list.html', [
                    'cart' => $cart,
                ]),
            ]);
        }

        return $this->forward([$this, 'listAction'], [
            'request' => $request,
        ], $request->query->all());
    }

    protected function forwardToList(Request $request)
    {
        return $this->forward([$this, 'listAction'], [
            'request' => $request,
        ], $request->query->all());
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    protected function isAcceptJson(Request $request)
    {
        return in_array('application/json', $request->getAcceptableContentTypes());
    }

    /**
     * @return CartInterface
     */
    private function getCart()
    {
        return $this->container->get('rise.bundle.cart.component.cart');
    }
}
