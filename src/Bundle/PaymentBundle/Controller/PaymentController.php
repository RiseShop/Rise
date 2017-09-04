<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\PaymentBundle\Controller;

use Mindy\Bundle\MindyBundle\Controller\Controller;
use Rise\Bundle\OrderBundle\Model\Order;
use Rise\Bundle\OrderBundle\Model\OrderInterface;
use Rise\Bundle\PaymentBundle\Model\Attempt;
use Rise\Bundle\PaymentBundle\Model\Payment;
use Rise\Component\Payment\FormResponseInterface;
use Rise\Component\Payment\Gateway\GatewayInterface;
use Rise\Component\Payment\OfflinePurchaseResponse;
use Rise\Component\Payment\OrderNumberInterface;
use Rise\Component\Payment\PrePaymentInterface;
use Rise\Component\Payment\PurchaseParameters;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PaymentController extends Controller
{
    private function getFactory()
    {
        return $this->get('rise.bundle.payment.factory');
    }

    public function validateAction(Request $request, $id)
    {
        /** @var Order|OrderInterface $order */
        $order = Order::objects()->get(['id' => $id]);
        if (null === $order) {
            throw new NotFoundHttpException();
        }

        $factory = $this->getFactory();
        /** @var GatewayInterface $gateway */
        $gateway = $factory->getGateway($order->payment->gateway);
        if (false === $gateway->supportValidate()) {
            throw new NotFoundHttpException();
        }

        return $gateway->validate($request);
    }

    public function completeAction(Request $request, $id)
    {
        /** @var Order|OrderInterface $order */
        $order = Order::objects()->get(['id' => $id]);
        if (null === $order) {
            throw new NotFoundHttpException();
        }

        $factory = $this->getFactory();
        /** @var GatewayInterface $gateway */
        $gateway = $factory->getGateway($order->payment->gateway);
        if (false === $gateway->supportValidate()) {
            throw new NotFoundHttpException();
        }

        /*
         * Платеж успешно проведен. Сохраняем статус заказа и
         * перенаправляем пользователя на заказ.
         */
        /*
         * TODO
        $attempt->is_success = true;
        if (false === $attempt->save()) {
            throw new \RuntimeException();
        }

        $order->status_id = Order::STATUS_PAIDED;
        if (false === $order->save()) {
            throw new \RuntimeException();
        }

        $this->addFlash('success', 'Заказ успешно оплачен');
        */

        return $gateway->complete($request);
    }

    public function purchaseAction(Request $request, $id)
    {
        /** @var Order|OrderInterface $order */
        $order = Order::objects()->get(['id' => $id]);
        if (null === $order) {
            throw new NotFoundHttpException();
        }

        $factory = $this->getFactory();
        /** @var GatewayInterface $gateway */
        $gateway = $factory->getGateway($order->payment->gateway);

        $attempt = new Attempt([
            'payment' => $order->payment,
            'order' => $order,
        ]);
        if (false === $attempt->save()) {
            throw new \RuntimeException('Failed to save Payment Attempt');
        }

        $purchaseParameters = new PurchaseParameters();
        $purchaseParameters->setOrder($order);
        $purchaseParameters->setCustomer($order->customer);
        $purchaseParameters->setAttempt($attempt);

        $purchaseResponse = $gateway->purchase($purchaseParameters);

        if ($purchaseResponse instanceof OfflinePurchaseResponse) {
            /*
             * Офлайн покупка.
             *
             * Примеры:
             *
             * > Получение наличных при передаче товара курьеру
             * > Самовывоз со склада и оплата по факту получения товара
             *
             * и т.д.
             */
            throw new \Exception('TODO');
        } elseif ($purchaseResponse instanceof FormResponseInterface) {
            /*
             * Обработка и создание формы для отправки данных
             * с клиента. Требуется, например, для яндекс кассы
             */
            $response = new Response($purchaseResponse->buildForm());

            return $this->preventCache($response);
        } elseif ($purchaseResponse->isSuccess()) {
            /*
             * Платеж успешно проведен без дополнительных проверок.
             * Сохраняем статус заказа и перенаправляем пользователя
             * на заказ.
             */
            $attempt->is_success = true;
            if (false === $attempt->save()) {
                throw new \RuntimeException();
            }

            if ($gateway instanceof PrePaymentInterface) {
                $order->status_id = Order::STATUS_PRE_PAIDED;
                if (false === $order->save()) {
                    throw new \RuntimeException();
                }

                // todo order pre-paided event
            } else {
                $order->status_id = Order::STATUS_PAIDED;
                if (false === $order->save()) {
                    throw new \RuntimeException();
                }

                // todo order paided event
            }

            $this->addFlash('success', 'Заказ успешно оплачен');

            return $this->redirectToRoute('shop_order_view', [
                'id' => $order->id,
                'token' => $order->token,
            ]);
        } elseif ($purchaseResponse->isRedirect()) {
            if ($purchaseResponse instanceof OrderNumberInterface) {
                $order->payment_order_id = $purchaseResponse->getOrderNumber();
                $order->save();
            }

            /*
             * Перенаправление пользователя на страницу платежной
             * системы.
             */
            $response = $this->redirect($purchaseResponse->getRedirectUrl());

            return $this->preventCache($response);
        } elseif ($purchaseResponse->isCancelled()) {
            /*
             * Платеж отменен пользователем. Сохраняем статус в попытке
             * платежа для информации, для менеджеров и перенаправляем
             * пользователя на страницу заказа.
             */
            $attempt->is_cancel = true;
            if (false === $attempt->save()) {
                throw new \RuntimeException();
            }

            $this->addFlash('warning', 'Платеж отменен');

//            return $this->redirectToRoute('shop_order_view', [
//                'id' => $order->id,
//                'token' => $order->token,
//            ]);
        } else {
            /*
             * Во время (попытки) проведения платежа произошла ошибка
             * на стороне платежного шлюза или на стороне сайта.
             * Перенаправляем пользователя на заказ.
             */
            $attempt->is_fail = true;
            $attempt->fail_error = $purchaseResponse->getError();
            if (false === $attempt->save()) {
                throw new \RuntimeException();
            }

            $this->addFlash('error', 'При проведении платежа возникла ошибка, пожалуйста повторите оплату позже');

//            return $this->redirectToRoute('shop_order_view', [
//                'id' => $order->id,
//                'token' => $order->token,
//            ]);
        }

        throw new \RuntimeException('Unknown payment state');
    }

    /**
     * @param Response $response
     *
     * @return Response
     */
    protected function preventCache(Response $response)
    {
        $response->headers->addCacheControlDirective('no-cache', true);
        $response->headers->addCacheControlDirective('max-age', 0);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->addCacheControlDirective('no-store', true);

        return $response;
    }
}
