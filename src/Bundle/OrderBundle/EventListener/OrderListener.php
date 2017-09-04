<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\OrderBundle\EventListener;

use Mindy\Bundle\MailBundle\Helper\Mail;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class OrderListener.
 */
class OrderListener
{
    /**
     * @var Mail
     */
    protected $mail;
    /**
     * @var UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * OrderListener constructor.
     *
     * @param Mail                  $mail
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(Mail $mail, UrlGeneratorInterface $urlGenerator)
    {
        $this->mail = $mail;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Событие при создании заказа.
     * Отправляются уведомления менеджерам и клиенту.
     *
     * @param OrderCreateEvent $event
     */
    public function onCreate(OrderCreateEvent $event)
    {
        $order = $event->getOrder();

        $url = $this->urlGenerator->generate('shop_order_view', [
            'id' => $order->id,
            'token' => $order->token,
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $customer = $order->customer;

        $subject = sprintf('Заказ №%s создан', $order->id);
        $this->mail->send($subject, $customer->email, 'rise/order/mail/order_create', [
            'order' => $order,
            'customer' => $customer,
            'order_url' => $url,
            'products' => $order->products->all(),
        ]);
    }

    /**
     * Событие при сохранении трекинг номера заказа.
     * Отправляются уведомления только клиенту.
     *
     * @param OrderTrackNumberEvent $event
     */
    public function onTrackNumber(OrderTrackNumberEvent $event)
    {
        $order = $event->getOrder();

        $url = $this->urlGenerator->generate('shop_order_view', [
            'id' => $order->id,
            'token' => $order->token,
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $customer = $order->customer;
        if (empty($order->customer)) {
            return;
        }

        $subject = sprintf(
            'Вашему заказу №%s присвоен номер отслеживания',
            $order->id
        );
        $this->mail->send($subject, $customer->email, 'rise/order/mail/order_track_number', [
            'order' => $order,
            'customer' => $customer,
            'order_url' => $url,
            'products' => $order->products->all(),
        ]);
    }

    /**
     * Событие при изменении статуса заказа.
     * Отправляются уведомления только клиенту.
     *
     * @param OrderChangeStatusEvent $event
     */
    public function onChangeStatus(OrderChangeStatusEvent $event)
    {
        $order = $event->getOrder();

        $url = $this->urlGenerator->generate('shop_order_view', [
            'id' => $order->id,
            'token' => $order->token,
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        if (empty($order->customer)) {
            return;
        }

        $customer = $order->customer;
        $subject = sprintf(
            'Изменился статус вашего заказа №%s',
            $order->id
        );
        $this->mail->send($subject, $customer->email, 'rise/order/mail/order_change_status', [
            'order' => $order,
            'customer' => $customer,
            'order_url' => $url,
            'products' => $order->products->all(),
        ]);
    }
}
