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
use Rise\Bundle\ProductBundle\Form\ProductFilterFormType;
use Rise\Bundle\ProductBundle\Form\ProductForm;
use Rise\Bundle\ProductBundle\Model\Image;
use Rise\Bundle\ProductBundle\Model\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductAdmin extends AbstractModelAdmin
{
    public $defaultOrder = ['-id'];

    /**
     * @var array
     */
    public $columns = ['id', 'name', 'entity'];

    public function getFilterFormType()
    {
        return ProductFilterFormType::class;
    }

    /**
     * @return string model class name
     */
    public function getModelClass()
    {
        return Product::class;
    }

    public function imageUploadAction(Request $request)
    {
        foreach ($request->files->get('images', []) as $image) {
            $image = new Image([
                'product_id' => $request->query->getInt('pk'),
                'image' => $image,
            ]);
            $image->save();
        }

        return $this->json([
            'status' => true,
        ]);
    }

    public function createAction(Request $request)
    {
        $product = (new \ReflectionClass($this->getModelClass()))->newInstance();

        $form = $this->createForm($this->getFormType(), $product, [
            'method' => 'POST',
            'attr' => ['enctype' => 'multipart/form-data'],
        ]);

        if ($request->getMethod() === 'POST') {
            if ($form->handleRequest($request)->isValid()) {
                $product = $form->getData();

                if ($product->save()) {
                    //                    $variant = $form->get('variant')->getData();
//                    $variant->name = $product->name;
//                    $variant->product = $product;
//                    $variant->save();

                    $this->addFlash(self::FLASH_SUCCESS, $this->get('translator')->trans('admin.flash.success'));

                    return $this->redirect($this->getAdminUrl('list', ['pk' => $product->pk]));
                }
            }
        }

        return $this->render($this->findTemplate('create.html'), [
            'form' => $form->createView(),
            'breadcrumbs' => $this->fetchBreadcrumbs($request, $product, 'create'),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function infoAction(Request $request)
    {
        $pk = $request->query->get('pk');
        if (empty($pk)) {
            throw new NotFoundHttpException();
        }

        $product = Product::objects()->get(['id' => $pk]);
        if ($product === null) {
            throw new NotFoundHttpException();
        }

        return $this->render($this->findTemplate('info.html'), [
            'model' => $product,
            'images' => $product->images->all(),
            'variants' => $product->variants->order(['-is_master'])->all(),
            'categories' => $product->category->all(),
        ]);
    }

    public function getFormType()
    {
        return ProductForm::class;
    }
}
