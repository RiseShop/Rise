<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\PaymentBundle\Command;

use Rise\Bundle\OrderBundle\Model\Order;
use Rise\Component\Payment\Gateway\GatewayInterface;
use Rise\Component\Payment\PurchaseParameters;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PaymentCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('payment:payment');
    }

    private function getFactory()
    {
        return $this->getContainer()->get('rise.bundle.payment.factory');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /*
         * Проверяем только новые заказы и только проверенные
         * менеджером (STATUS_VERIFIED)
         */

        /** @var Order[] $orders */
        $orders = Order::objects()
            ->filter(['status_id' => Order::STATUS_NEW]);

        foreach ($orders as $order) {
            $factory = $this->getFactory();
            $gatewayName = $order->payment->gateway;

            /** @var GatewayInterface $gateway */
            $gateway = $factory->getGateway($gatewayName);

            if ($gateway->supportComplete()) {
                $purchaseParameters = new PurchaseParameters();
                $purchaseParameters->setOrder($order);
                $purchaseParameters->setCustomer($order->customer);

                $response = $gateway->complete($purchaseParameters);
                if ($response->isSuccess()) {
                    $order->status_id = Order::STATUS_PAIDED;
                    if (false === $order->save()) {
                        throw new \RuntimeException();
                    }

                    // TODO send paided event
                }
            }
        }
    }
}
