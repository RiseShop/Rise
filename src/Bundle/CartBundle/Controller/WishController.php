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
use Rise\Bundle\ProductBundle\Model\Variant;
use Rise\Bundle\CartBundle\Model\Wish;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WishController extends Controller
{
    public function listAction(Request $request)
    {
        if (false === $this->isGranted('IS_AUTHENTICATED_FULLY')) {
            if ($request->isXmlHttpRequest()) {
                return $this->json([
                    'error' => 'Требуется авторизация'
                ]);
            }
        }

        $user = $this->getUser();

        $qs = Wish::objects()
            ->filter(['user' => $user])
            ->order(['-id']);

        $pager = $this->createPagination($qs);

        return $this->render('rise/wish/list.html', [
            'wish' => $pager->paginate(),
            'pager' => $pager->createView(),
        ]);
    }

    public function removeAction(Request $request)
    {
        $id = $request->request->get('id');
        if (empty($id)) {
            throw new NotFoundHttpException();
        }

        $wish = Wish::objects()->get(['id' => $id]);
        if (null === $wish) {
            throw new NotFoundHttpException();
        }

        if ($this->isAcceptJson($request)) {
            $qs = Wish::objects()
                ->filter([
                    'user' => $this->getUser(),
                ])
                ->order(['-id']);

            $pager = $this->createPagination($qs);

            $html = $this->renderTemplate('rise/wish/list.html', [
                'wish' => $pager->paginate(),
                'pager' => $pager->createView(),
            ]);

            return $this->json([
                'status' => true,
                'html' => $html,
            ]);
        }

        return $this->redirectToRoute('rise_wish_list');
    }

    public function addAction(Request $request)
    {
        if (false === $this->isGranted('IS_AUTHENTICATED_FULLY')) {
            if ($this->isAcceptJson($request)) {
                return $this->json([
                    'status' => false,
                    'error' => 'Требуется авторизация',
                    'html' => $this->renderTemplate('rise/wish/auth_required.html'),
                ]);
            }

            return $this->render('rise/wish/auth_required.html');
        }

        $id = $request->request->get('id');
        if (empty($id)) {
            throw new NotFoundHttpException();
        }

        if (Wish::objects()->filter(['variant_id' => $id])->count() > 0) {
            return $this->json([
                'status' => true,
            ]);
        }

        $variant = Variant::objects()->get(['id' => $id]);
        if (null === $variant) {
            throw new NotFoundHttpException();
        }

        $wish = new Wish([
            'user' => $this->getUser(),
            'variant' => $variant,
        ]);
        if (false === $wish->save()) {
            throw new RuntimeException();
        }

        if ($this->isAcceptJson($request)) {
            $qs = Wish::objects()
                ->filter(['user' => $this->getUser()])
                ->order(['-id']);

            $pager = $this->createPagination($qs);

            $html = $this->renderTemplate('rise/wish/list.html', [
                'wish' => $pager->paginate(),
                'pager' => $pager->createView(),
            ]);

            return $this->json([
                'count' => Wish::objects()->filter(['user' => $this->getUser()])->count(),
                'status' => true,
                'html' => $html,
            ]);
        }

        return $this->redirectToRoute('rise_wish_list');
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
}
