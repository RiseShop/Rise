<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\ProductBundle\Admin;

use Mindy\Bundle\AdminBundle\Admin\AbstractModelAdmin;
use Rise\Bundle\ProductBundle\Form\VariantFormType;
use Rise\Bundle\ProductBundle\Model\Product;
use Rise\Bundle\ProductBundle\Model\ValueVariant;
use Rise\Bundle\ProductBundle\Model\Variant;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VariantAdmin extends AbstractModelAdmin
{
    public function getFormType()
    {
        return VariantFormType::class;
    }

    public function getModelClass()
    {
        return Variant::class;
    }

    public function createAction(Request $request)
    {
        $product = Product::objects()->get([
            'id' => $request->query->getInt('product_id'),
        ]);
        if (null === $product) {
            throw new NotFoundHttpException();
        }

        $variant = new Variant([
            'name' => $product->name,
            'product' => $product,
        ]);

        $form = $this->createForm($this->getFormType(), $variant, [
            'method' => 'POST',
            'product' => $product,
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $variant = $form->getData();
            $variant->save();

            return $this->redirectToRoute('admin_dispatch', [
                'bundle' => 'ProductBundle',
                'admin' => 'ProductAdmin',
                'action' => 'info',
                'pk' => $product->id,
            ]);
        }

        return $this->render($this->findTemplate('create.html'), [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    public function updateAction(Request $request)
    {
        $product = Product::objects()->get([
            'id' => $request->query->getInt('product_id'),
        ]);
        if (null === $product) {
            throw new NotFoundHttpException();
        }

        $variant = Variant::objects()->get([
            'id' => $request->query->getInt('pk'),
            'product' => $product,
        ]);

        $form = $this->createForm($this->getFormType(), $variant, [
            'method' => 'POST',
            'product' => $product,
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $variant = $form->getData();
            $variant->save();

            $values = $form->get('eav')->getData();
            foreach ($values as $code => $value) {
                $valueVariant = new ValueVariant([
                    'variant' => $variant,
                    'attribute_code' => $code,
                    'value' => $value,
                ]);
                $valueVariant->save();
            }

            return $this->redirectToRoute('admin_dispatch', [
                'bundle' => 'ProductBundle',
                'admin' => 'ProductAdmin',
                'action' => 'info',
                'pk' => $product->id,
            ]);
        }

        return $this->render($this->findTemplate('update.html'), [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }
}
