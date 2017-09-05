<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\OrderBundle\Admin;

use Mindy\Bundle\AdminBundle\Admin\AbstractModelAdmin;
use Mindy\Bundle\AdminBundle\Form\DeleteConfirmForm;
use Mindy\Orm\ModelInterface;
use Rise\Bundle\OrderBundle\EventListener\OrderTrackNumberEvent;
use Rise\Bundle\OrderBundle\Form\CommentForm;
use Rise\Bundle\OrderBundle\Form\CustomerForm;
use Rise\Bundle\OrderBundle\Form\Order\CustomerSelectForm;
use Rise\Bundle\OrderBundle\Form\Order\PriceDeliveryForm;
use Rise\Bundle\OrderBundle\Form\Order\DeliveryForm;
use Rise\Bundle\OrderBundle\Form\Order\DiscountForm;
use Rise\Bundle\OrderBundle\Form\Order\OrderProductForm;
use Rise\Bundle\OrderBundle\Form\Order\PaymentForm;
use Rise\Bundle\OrderBundle\Form\Order\StatusForm;
use Rise\Bundle\OrderBundle\Form\Order\TrackNumberForm;
use Rise\Bundle\OrderBundle\Form\Order\UserForm;
use Rise\Bundle\OrderBundle\Form\OrderForm;
use Rise\Bundle\OrderBundle\Model\Comment;
use Rise\Bundle\OrderBundle\Model\Customer;
use Rise\Bundle\OrderBundle\Model\Order;
use Rise\Bundle\OrderBundle\Model\OrderProduct;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderAdmin extends AbstractModelAdmin
{
    public $columns = ['customer', 'status_id', 'delivery', 'payment', 'price', 'created_at'];

    public $permissions = [
        'update' => false
    ];

    public function getVerboseName($column)
    {
        switch ($column) {
            case 'price':
                return 'Сумма';
        }

        return parent::getVerboseName($column);
    }

    /**
     * @return string model class name
     */
    public function getModelClass()
    {
        return Order::class;
    }

    public function statusAction(Request $request)
    {
        $order = Order::objects()->get([
            'pk' => $request->query->getInt('pk'),
        ]);
        if (null === $order) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(StatusForm::class, $order, [
            'method' => 'POST',
            'action' => $this->getAdminUrl('status', ['pk' => $order->id]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            if (false === $order->save()) {
                throw new \RuntimeException();
            }

            return $this->redirect($this->getAdminUrl('info', [
                'pk' => $order->id,
            ]));
        }

        return $this->render($this->findTemplate('change_status.html'), [
            'order' => $order,
            'form' => $form->createView(),
            'breadcrumbs' => $this->fetchBreadcrumbs($request, $order, 'status')
        ]);
    }

    public function getCustomBreadrumbs(Request $request, ModelInterface $model, $action)
    {
        $breadcrumbs = parent::getCustomBreadrumbs($request, $model, $action);

        switch ($action) {
            case 'status':
                $breadcrumbs = array_merge($breadcrumbs, [
                    ['name' => (string)$model, 'url' => $this->getAdminUrl('info', ['pk' => $model->pk])],
                    ['name' => 'Изменение статуса заказа']
                ]);
                break;
            case 'payment':
                $breadcrumbs = array_merge($breadcrumbs, [
                    ['name' => (string)$model, 'url' => $this->getAdminUrl('info', ['pk' => $model->pk])],
                    ['name' => 'Изменение способа оплаты']
                ]);
                break;
            case 'delivery':
                $breadcrumbs = array_merge($breadcrumbs, [
                    ['name' => (string)$model, 'url' => $this->getAdminUrl('info', ['pk' => $model->pk])],
                    ['name' => 'Изменение способа доставки']
                ]);
                break;
            case 'track_number':
                $breadcrumbs = array_merge($breadcrumbs, [
                    ['name' => (string)$model, 'url' => $this->getAdminUrl('info', ['pk' => $model->pk])],
                    ['name' => 'Изменение номера отслеживания']
                ]);
                break;
            case 'product_add':
                $breadcrumbs = array_merge($breadcrumbs, [
                    ['name' => (string)$model, 'url' => $this->getAdminUrl('info', ['pk' => $model->pk])],
                    ['name' => 'Добавление товара к заказу']
                ]);
                break;
            case 'product_update':
                $breadcrumbs = array_merge($breadcrumbs, [
                    ['name' => (string)$model, 'url' => $this->getAdminUrl('info', ['pk' => $model->pk])],
                    ['name' => 'Изменение товара в заказе']
                ]);
                break;
            case 'product_remove':
                $breadcrumbs = array_merge($breadcrumbs, [
                    ['name' => (string)$model, 'url' => $this->getAdminUrl('info', ['pk' => $model->pk])],
                    ['name' => 'Удаление товара из заказа']
                ]);
                break;
            case 'discount':
                $breadcrumbs = array_merge($breadcrumbs, [
                    ['name' => (string)$model, 'url' => $this->getAdminUrl('info', ['pk' => $model->pk])],
                    ['name' => 'Изменение скидки']
                ]);
                break;
            case 'price_delivery':
                $breadcrumbs = array_merge($breadcrumbs, [
                    ['name' => (string)$model, 'url' => $this->getAdminUrl('info', ['pk' => $model->pk])],
                    ['name' => 'Изменение стоимости доставки']
                ]);
                break;
        }

        return $breadcrumbs;
    }

    public function deliveryAction(Request $request)
    {
        $order = Order::objects()->get([
            'pk' => $request->query->getInt('pk'),
        ]);
        if (null === $order) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(DeliveryForm::class, $order, [
            'method' => 'POST',
            'action' => $this->getAdminUrl('delivery', ['pk' => $order->id]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            if (false === $order->save()) {
                throw new \RuntimeException();
            }

            return $this->redirect($this->getAdminUrl('info', [
                'pk' => $order->id,
            ]));
        }

        return $this->render($this->findTemplate('delivery_method.html'), [
            'order' => $order,
            'form' => $form->createView(),
            'breadcrumbs' => $this->fetchBreadcrumbs($request, $order, 'delivery')
        ]);
    }

    public function paymentAction(Request $request)
    {
        $order = Order::objects()->get([
            'pk' => $request->query->getInt('pk'),
        ]);
        if (null === $order) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(PaymentForm::class, $order, [
            'method' => 'POST',
            'action' => $this->getAdminUrl('payment', ['pk' => $order->id]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            if (false === $order->save()) {
                throw new \RuntimeException();
            }

            return $this->redirect($this->getAdminUrl('info', [
                'pk' => $order->id,
            ]));
        }

        return $this->render($this->findTemplate('payment_gateway.html'), [
            'order' => $order,
            'form' => $form->createView(),
            'breadcrumbs' => $this->fetchBreadcrumbs($request, $order, 'payment')
        ]);
    }

    public function discountAction(Request $request)
    {
        $order = Order::objects()->get([
            'pk' => $request->query->getInt('pk'),
        ]);
        if (null === $order) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(DiscountForm::class, $order, [
            'method' => 'POST',
            'action' => $this->getAdminUrl('discount', ['pk' => $order->id]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            if (false === $order->save()) {
                throw new \RuntimeException();
            }

            return $this->redirect($this->getAdminUrl('info', [
                'pk' => $order->id,
            ]));
        }

        return $this->render($this->findTemplate('discount.html'), [
            'order' => $order,
            'form' => $form->createView(),
            'breadcrumbs' => $this->fetchBreadcrumbs($request, $order, 'discount')
        ]);
    }

    public function priceDeliveryAction(Request $request)
    {
        $order = Order::objects()->get([
            'pk' => $request->query->getInt('pk'),
        ]);
        if (null === $order) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(PriceDeliveryForm::class, $order, [
            'method' => 'POST',
            'action' => $this->getAdminUrl('priceDelivery', ['pk' => $order->id]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            if (false === $order->save()) {
                throw new \RuntimeException();
            }

            return $this->redirect($this->getAdminUrl('info', [
                'pk' => $order->id,
            ]));
        }

        return $this->render($this->findTemplate('price_delivery.html'), [
            'order' => $order,
            'form' => $form->createView(),
            'breadcrumbs' => $this->fetchBreadcrumbs($request, $order, 'price_delivery')
        ]);
    }

    public function trackNumberAction(Request $request)
    {
        $order = Order::objects()->get([
            'pk' => $request->query->getInt('pk'),
        ]);
        if (null === $order) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(TrackNumberForm::class, $order, [
            'method' => 'POST',
            'action' => $this->getAdminUrl('trackNumber', ['pk' => $order->id]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            if (false === $order->save()) {
                throw new \RuntimeException('Error while save comment');
            }

            $this->get('event_dispatcher')->dispatch(
                OrderTrackNumberEvent::NAME,
                new OrderTrackNumberEvent($order)
            );

            $this->addFlash(self::FLASH_SUCCESS, 'Номер отслеживания успешно присвоен. Пользователю отправлено уведомление.');

            return $this->redirect($this->getAdminUrl('info', [
                'pk' => $order->id,
            ]));
        }

        return $this->render($this->findTemplate('track_number.html'), [
            'order' => $order,
            'form' => $form->createView(),
            'breadcrumbs' => $this->fetchBreadcrumbs($request, $order, 'track_number')
        ]);
    }

    public function userAction(Request $request)
    {
        $order = Order::objects()->get([
            'pk' => $request->query->getInt('pk'),
        ]);
        if (null === $order) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(UserForm::class, $order, [
            'method' => 'POST',
            'action' => $this->getAdminUrl('user', ['pk' => $order->id]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            if (false === $order->save()) {
                throw new \RuntimeException('Error while save comment');
            }

            $this->addFlash(self::FLASH_SUCCESS, 'Номер отслеживания успешно присвоен. Пользователю отправлено уведомление.');

            return $this->redirect($this->getAdminUrl('info', [
                'pk' => $order->id,
            ]));
        }

        return $this->render($this->findTemplate('track_number.html'), [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    public function customerSelectAction(Request $request)
    {
        $order = Order::objects()->get([
            'pk' => $request->query->getInt('pk'),
        ]);
        if (null === $order) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(CustomerSelectForm::class, $order, [
            'method' => 'POST',
            'action' => $this->getAdminUrl('customerSelect', ['pk' => $order->id]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            if (false === $order->save()) {
                throw new \RuntimeException('Error while save comment');
            }

            $this->addFlash(self::FLASH_SUCCESS, 'Номер отслеживания успешно присвоен. Пользователю отправлено уведомление.');

            return $this->redirect($this->getAdminUrl('info', [
                'pk' => $order->id,
            ]));
        }

        return $this->render($this->findTemplate('customer_select.html'), [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    public function productAddAction(Request $request)
    {
        $order = Order::objects()->get([
            'pk' => $request->query->getInt('pk'),
        ]);
        if (null === $order) {
            throw new NotFoundHttpException();
        }

        $product = new OrderProduct([
            'order' => $order,
        ]);
        $form = $this->createForm(OrderProductForm::class, $product, [
            'method' => 'POST',
            'action' => $this->getAdminUrl('productAdd', ['pk' => $order->id]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $raw = $form->getData();
            $variant = $raw['variant'];

            $product = new OrderProduct([
                'order' => $order,
                'variant' => $variant,
                'discount' => $raw['discount'],
                'quantity' => $raw['quantity'],
                'name' => $variant->name,
                'sku' => $variant->sku,
                'price' => $variant->price,
            ]);

            if (false === $product->save()) {
                throw new \RuntimeException('Error while save customer');
            }

            $this->addFlash(self::FLASH_SUCCESS, 'Товар добавлен к заказу');

            return $this->redirect($this->getAdminUrl('info', [
                'pk' => $order->id,
            ]));
        }

        return $this->render($this->findTemplate('product_add.html'), [
            'order' => $order,
            'form' => $form->createView(),
            'breadcrumbs' => $this->fetchBreadcrumbs($request, $order, 'product_add')
        ]);
    }

    public function customerCreateAction(Request $request)
    {
        $order = Order::objects()->get([
            'pk' => $request->query->getInt('pk'),
        ]);
        if (null === $order) {
            throw new NotFoundHttpException();
        }

        $customer = new Customer();
        $form = $this->createForm(CustomerForm::class, $customer, [
            'method' => 'POST',
            'action' => $this->getAdminUrl('customerCreate', ['pk' => $order->id]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $customer = $form->getData();
            if (false === $customer->save()) {
                throw new \RuntimeException('Error while save customer');
            }

            $order->customer = $customer;
            if (false === $order->save()) {
                throw new \RuntimeException('Error while save order');
            }

            $this->addFlash(self::FLASH_SUCCESS, 'Номер отслеживания успешно присвоен. Пользователю отправлено уведомление.');

            return $this->redirect($this->getAdminUrl('info', [
                'pk' => $order->id,
            ]));
        }

        return $this->render($this->findTemplate('customer_select.html'), [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    public function createAction(Request $request)
    {
        $order = new Order([
            'status_id' => Order::STATUS_DRAFT,
        ]);
        if ($order->save()) {
            return $this->redirect($this->getAdminUrl('info', ['pk' => $order->id]));
        }

        throw new \RuntimeException('Failed to save order');
    }

    public function updateAction(Request $request)
    {
        return $this->redirect($this->getAdminUrl('info', [
            'pk' => $request->query->get('pk')
        ]));
    }

    public function infoAction(Request $request)
    {
        $pk = $request->query->getInt('pk');
        $order = Order::objects()->get(['id' => $pk]);

        if ($order === null) {
            throw new NotFoundHttpException();
        }

        $products = $order->products->all();

        $user = $this->getUser();

        $comment = new Comment([
            'order' => $order,
            'user' => $user instanceof ModelInterface ? $user : null,
        ]);
        $commentForm = $this->createForm(CommentForm::class, $comment, [
            'method' => 'POST',
        ]);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment = $commentForm->getData();
            if (false === $comment->save()) {
                throw new \RuntimeException('Error while save comment');
            }

            $this->addFlash(self::FLASH_SUCCESS, 'Комментарий сохранен. Пользователю отправлено уведомление.');

            return $this->redirect($request->getRequestUri());
        }

        return $this->render($this->findTemplate('info.html'), [
            'model' => $order,
            'customer' => $order->customer,
            'comments' => $order->comments->all(),
            'products' => $products,
            'commentForm' => $commentForm->createView(),
            'breadcrumbs' => $this->fetchBreadcrumbs($request, $order, 'info'),
        ]);
    }

    public function productRemoveAction(Request $request)
    {
        $order = Order::objects()->get([
            'id' => $request->query->getInt('pk'),
        ]);
        if ($order === null) {
            throw new NotFoundHttpException();
        }

        $product = OrderProduct::objects()->get([
            'pk' => $request->query->getInt('product_id'),
            'order' => $order,
        ]);
        if (null === $product) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(DeleteConfirmForm::class, [], [
            'method' => 'POST',
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product->delete();

            return $this->redirect($this->getAdminUrl('info', [
                'pk' => $order->id,
            ]));
        }

        return $this->render($this->findTemplate('product_delete.html'), [
            'form' => $form->createView(),
            'order' => $order,
            'product' => $product,
            'breadcrumbs' => $this->fetchBreadcrumbs($request, $order, 'product_remove')
        ]);
    }

    public function productUpdateAction(Request $request)
    {
        $order = Order::objects()->get([
            'id' => $request->query->getInt('pk'),
        ]);
        if ($order === null) {
            throw new NotFoundHttpException();
        }

        $product = OrderProduct::objects()->get([
            'pk' => $request->query->getInt('product_id'),
            'order' => $order,
        ]);
        if (null === $product) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(OrderProductForm::class, $product, [
            'method' => 'POST',
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $product->name = (string)$product->variant;
            $product->save();

            return $this->redirect($this->getAdminUrl('info', [
                'pk' => $order->id,
            ]));
        }

        return $this->render($this->findTemplate('product_update.html'), [
            'form' => $form->createView(),
            'breadcrumbs' => $this->fetchBreadcrumbs($request, $order, 'product_update')
        ]);
    }

    public function getFormType()
    {
        return OrderForm::class;
    }
}
