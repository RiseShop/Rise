<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Payment\Gateway\Yandex;

use Rise\Component\Payment\AbstractGateway;
use Rise\Component\Payment\PurchaseParametersInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Yandex extends AbstractGateway
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'shopId' => null,
            'scId' => null,
            'shop_password' => null,
        ]);
    }

    public function purchase(PurchaseParametersInterface $parameters)
    {
        $order = $parameters->getOrder();
        $customer = $parameters->getCustomer();

        return new YandexPurchaseResponse(array_merge($this->getBag()->all(), [
            'sum' => $this->formatAmount($order->getPriceTotal()),
            'orderNumber' => $order->getId(),
            'customerNumber' => $customer->getId(),
            'cps_phone' => $customer->getPhone(),
            'cps_email' => $customer->getEmail(),
        ]), $this->testMode);
    }

    public function getName()
    {
        return 'Яндекс Касса';
    }

    protected function generateHash(Request $request)
    {
        $hashString = implode(';', [
            $request->request->get('action'),
            $request->request->get('orderSumAmount'),
            $request->request->get('orderSumCurrencyPaycash'),
            $request->request->get('orderSumBankPaycash'),
            $this->parametersBag->get('shopId'),
            $request->request->get('invoiceId'),
            $request->request->get('customerNumber'),
            $this->parametersBag->get('shop_password'),
        ]);

        return strtolower(md5($hashString));
    }

    protected function xml($content)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'xml');
        $response->setContent($content);

        return $response;
    }

    public function validate(Request $request)
    {
        $content = sprintf(
            '<?xml version="1.0" encoding="UTF-8"?><checkOrderResponse performedDatetime="%s" code="%s" invoiceId="%s" shopId="%s"/>',
            $request->request->get('requestDatetime'),
            $this->validateCode($request),
            $request->request->get('invoiceId'),
            $this->parametersBag->get('shopId')
        );

        return $this->xml($content);
    }

    /**
     * @param Request $request
     *
     * @return int
     */
    protected function validateCode(Request $request)
    {
        // 1 - failure
        // 0 - success
        return (int) $this->generateHash($request) != strtolower($request->request->get('md5'));
    }

    public function complete(Request $request)
    {
        $content = sprintf(
            '<?xml version="1.0" encoding="UTF-8"?><paymentAvisoResponse performedDatetime="%s" code="%s" invoiceId="%s" shopId="%s"/>',
            $request->request->get('requestDatetime'),
            $this->validateCode($request),
            $request->request->get('invoiceId'),
            $this->parametersBag->get('shopId')
        );

        return $this->xml($content);
    }
}
