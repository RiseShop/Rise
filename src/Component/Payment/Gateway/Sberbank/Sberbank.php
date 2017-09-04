<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Payment\Gateway\Sberbank;

use Rise\Component\Payment\AbstractGateway;
use Rise\Component\Payment\PurchaseParametersInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Sberbank extends AbstractGateway
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'username',
            'password',
        ]);
    }

    public function getName()
    {
        return 'Сбербанк Онлайн';
    }

    public function getDefaultParameters()
    {
        return [
            // 30 min
            'sessionTimeoutSecs' => 60 * 30,
        ];
    }

    /**
     * Сбербанк принимает только сумму с 2 последними нулями в
     * качестве копеек. Если передать сумму в 100 то к оплате будет 1 рубль,
     * так как последние 2 нуля будут выступать в качестве копеек.
     * Следовательно, сумма которая передается будет умножена на 100.
     *
     * 100 рублей к оплате - 10000 (100.00) передаем в сбербанк
     *
     * @param $amount
     *
     * @return string
     */
    protected function formatAmount($amount)
    {
        if (false === strpos($amount, '.')) {
            $amount = sprintf('%d00', $amount);
        } else {
            $amount = str_replace('.', '', number_format($amount, 2, '.', ''));
        }

        return $amount;
    }

    public function purchase(PurchaseParametersInterface $parameters)
    {
        $attempt = $parameters->getAttempt();
        $order = $parameters->getOrder();

        $request = new SberbankPurchase(array_merge($this->getDefaultParameters(), [
            /*
             * http://www.rbspayment.ru/imgcompany/rbs/files/new-customers/Merchant-Manual-SBRF.pdf
             *
             * Используется не номер заказа, а номер попытки оплатить.
             * Логика сбербанка такова, что при исчерпании попыток оплатить (о_О)
             * или истечении времени заказа оплата становится невозможна
             * и нужно будет регистрировать номер заказа повторно.
             *
             * Проблема в статусах ответа:
             * 5 Исчерпаны попытки оплаты или закончилось время сессии
             *
             * Чтобы не ловить подобную логику и не обрабатывать ее, передаем
             * идентификатор попытки платежа.
             */
            'orderNumber' => sprintf('%s_%s', $order->getId(), $attempt->getId()),
            'amount' => $this->formatAmount($order->getPriceTotal()),

            /*
             * Будьте осторожны, в класс Sberbank приходит `username`, а
             * в `SberbankPurchase` должен уходить `userName`
             */
            'userName' => $this->getParameter('username'),
            'password' => $this->getParameter('password'),

            // TODO
            'returnUrl' => 'http://localhost:8000/return/sber',
            'failUrl' => 'http://localhost:8000/fail/sber',
        ]), $this->testMode);

        return new SberbankPurchaseResponse($request->send());
    }
}
