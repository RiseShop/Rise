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
use Rise\Bundle\OrderBundle\EventListener\OrderCreateEvent;
use Rise\Bundle\OrderBundle\Form\Wizard\Step1FormType;
use Rise\Bundle\OrderBundle\Form\Wizard\Step2FormType;
use Rise\Bundle\OrderBundle\Form\Wizard\Step3FormType;
use Rise\Bundle\OrderBundle\Form\Wizard\Step4FormType;
use Rise\Bundle\OrderBundle\Model\Customer;
use Rise\Bundle\OrderBundle\Model\Order;
use Rise\Bundle\OrderBundle\Model\OrderProduct;
use Rise\Bundle\PaymentBundle\Model\Payment;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WizardController extends Controller
{
    protected function getStorage()
    {
        return $this->get('rise.bundle.order.wizard.storage');
    }

    private function cleanData()
    {
        $steps = array_filter(array_map(function ($method) {
            if (false === strpos($method, 'process')) {
                return false;
            }

            return (int) str_replace('process', '', $method);
        }, get_class_methods($this)));

        foreach ($steps as $step) {
            $this->getStorage()->set($step, []);
        }
    }

    public function dispatchAction(Request $request, $step)
    {
        $method = sprintf('process%s', $step);
        if (method_exists($this, $method)) {
            return $this->$method($request, $step);
        }

        throw new NotFoundHttpException();
    }

    public function process1(Request $request, $step)
    {
        $form = $this->createForm(Step1FormType::class, $this->getStorage()->get($step), [
            'method' => 'POST',
        ]);

        if ($form->handleRequest($request)->isValid()) {
            $this->getStorage()->set($step, $form->getData());

            if ($form->get('next')->isClicked()) {
                return $this->redirectToRoute('shop_order_wizard', [
                    'step' => 2,
                ]);
            }

            return $this->redirect($request->getRequestUri());
        }

        return $this->render('rise/order/wizard/step1.html', [
            'form' => $form->createView(),
            'step' => $step,
        ]);
    }

    public function process2(Request $request, $step)
    {
        $form = $this->createForm(Step2FormType::class, $this->getStorage()->get($step), [
            'method' => 'POST',
        ]);

        if ($form->handleRequest($request)->isValid()) {
            $this->getStorage()->set($step, $form->getData());

            if ($form->get('next')->isClicked()) {
                return $this->redirectToRoute('shop_order_wizard', [
                    'step' => 3,
                ]);
            }

            return $this->redirectToRoute('shop_order_wizard', [
                'step' => 1,
            ]);
        }

        return $this->render('rise/order/wizard/step2.html', [
            'form' => $form->createView(),
            'step' => 2,
        ]);
    }

    public function process3(Request $request, $step)
    {
        $prevData = $this->getStorage()->get(2);
        $form = $this->createForm(Step3FormType::class, $this->getStorage()->get($step), [
            'method' => 'POST',
            'payment' => $prevData['payment'],
        ]);

        if ($form->handleRequest($request)->isValid()) {
            $this->getStorage()->set($step, $form->getData());

            if ($form->get('next')->isClicked()) {
                return $this->redirectToRoute('shop_order_wizard', [
                    'step' => 4,
                ]);
            }

            return $this->redirectToRoute('shop_order_wizard', [
                'step' => 2,
            ]);
        }

        return $this->render('rise/order/wizard/step3.html', [
            'form' => $form->createView(),
            'step' => $step,
        ]);
    }

    public function process4(Request $request, $step)
    {
        $form = $this->createForm(Step4FormType::class, $this->getStorage()->get($step), [
            'method' => 'POST',
        ]);

        if ($form->handleRequest($request)->isValid()) {
            $this->getStorage()->set($step, $form->getData());

            $data = $this->getStorage()->merge();

            $user = $this->getUser();

            if ($form->get('finish')->isClicked()) {
                // Create customer
                $customer = new Customer([
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'middle_name' => $data['middle_name'],
                    'country' => $data['country'],
                    'region' => $data['region'],
                    'city' => $data['city'],
                    'zip_code' => $data['zip_code'],
                    'address' => $data['address'],
                    'phone' => $data['phone'],
                    'email' => $data['email'],
                    'user' => $user,
                ]);
                if (false == $customer->save()) {
                    throw new RuntimeException('Failed to save customer');
                }

                $order = new Order([
                    'status_id' => Order::STATUS_VERIFICATION,
                    'user' => $user,
                    'customer' => $customer,
                    'payment' => $data['payment'],
                    'delivery' => $data['delivery'],
                    'comment' => $data['comment'],
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
                }

                if ($this->getParameter('rise.order.remove_data_on_order')) {
                    $this->cleanData();
                }

                $this->get('event_dispatcher')->dispatch(
                    OrderCreateEvent::NAME,
                    new OrderCreateEvent($order)
                );

                return $this->redirectToRoute('shop_order_view', [
                    'id' => $order->id,
                    'token' => $order->token,
                ]);
            }

            return $this->redirectToRoute('shop_order_wizard', [
                'step' => 3,
            ]);
        }

        return $this->render('rise/order/wizard/step4.html', [
            'form' => $form->createView(),
            'step' => $step,
        ]);
    }

    protected function getCart()
    {
        return $this->get('rise.bundle.cart.component.cart');
    }
}
