<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Bundle\ProductBundle\Controller;

use Mindy\Bundle\MindyBundle\Controller\Controller;
use Mindy\Orm\Manager;
use Mindy\Orm\QuerySet;
use Mindy\QueryBuilder\Aggregation\Count;
use Mindy\QueryBuilder\Q\QAnd;
use Mindy\QueryBuilder\Q\QOr;
use Rise\Bundle\ProductBundle\Form\FilterFormType;
use Rise\Bundle\ProductBundle\Model\Category;
use Rise\Bundle\ProductBundle\Model\Entity;
use Rise\Bundle\ProductBundle\Model\Variant;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VariantController extends Controller
{
    public function searchAction(Request $request)
    {
        $filterForm = $this->createForm(FilterFormType::class, $request->query->get('filter', []));

        $q = $request->query->get('q');
        if (
            empty($q) ||
            (false === empty($q) && mb_strlen($q, 'UTF-8') < 3) ||
            (false === empty($q) && mb_strlen($q, 'UTF-8') > 20)
        ) {
            return $this->render('rise/product/search_empty.html', [
                'filterForm' => $filterForm->createView(),
            ]);
        }

        $qs = Variant::objects()->published()->master()->filter(new QOr([
            ['name__icontains' => $q],
            ['description_short__icontains' => $q],
            ['description__icontains' => $q],
        ]));

        $filterForm->handleRequest($request);
        if ($filterForm->isSubmitted()) {
            $this->filterQuerySet($filterForm->getData(), $qs);
        }
        $pager = $this->createPagination($qs, [
            'pageSize' => 12,
        ]);

        return $this->render('rise/product/search.html', [
            'variants' => $pager->paginate(),
            'pager' => $pager->createView(),
            'filterForm' => $filterForm->createView(),
        ]);
    }

    public function listAction(Request $request, $slug)
    {
        /** @var Category $category */
        $category = Category::objects()->get(['slug' => $slug]);
        if ($category === null) {
            throw new NotFoundHttpException();
        }

        $filter = $request->query->get('filter', []);
        $filterForm = $this->createForm(FilterFormType::class, $filter, [
        ]);

        $qs = Variant::objects()->published()->master()->getQuerySet();

        $filterForm->handleRequest($request);
        if ($filterForm->isSubmitted()) {
            $this->filterQuerySet($filterForm->getData(), $qs);
        }

        $pager = $this->createPagination($qs);

        return $this->render('rise/product/list.html', [
            'variants' => $pager->paginate(),
            'pager' => $pager,
            'filterForm' => $filterForm->createView(),
        ]);
    }

    /**
     * @param array            $data
     * @param QuerySet|Manager $qs
     */
    protected function filterQuerySet(array $data, $qs)
    {
        /*
         * TODO use this solution, its better!
SELECT var.*, COUNT(v.variant_id)
FROM product_value_variant v
LEFT JOIN product_variant var ON var.id=v.variant_id
WHERE
    (
        v.attribute_code='metal' AND v.value_id=5487
    ) OR (
        v.attribute_code='size' AND v.value_id=5499
    ) OR (
        v.attribute_code='proba' AND v.value_id=5488
    )
GROUP by v.variant_id
HAVING COUNT(v.variant_id) = 3
         */

        unset($data['submit']);

        if (false == empty($data['price'])) {
            list($min, $max) = $data['price'];
            if ($min) {
                $qs->filter(['price__gte' => $min]);
            }
            if ($max) {
                $qs->filter(['price__lte' => $max]);
            }
        }
        unset($data['price']);

        if (false == empty($data['entity'])) {
            $qs->filter([
                'product__entity__id__in' => array_map(function (Entity $entity) {
                    return $entity->pk;
                }, $data['entity']),
            ]);
        }
        unset($data['entity']);

        $fields = array_filter($data);

        if (count($fields) > 0) {
            $qs->select([
                '*',
                new Count('q__variant_id', 'count'),
            ]);

            /*
             * Если в Variant связь будет называться `value`, до ValueVariant, внутри которой
             * будет так же связь с названием `value` до Value то выборки вида:
             * new QAnd(['value__attribute_code' => $code, 'value__value_id' => $valueId]);
             *
             * Не будут работать и будет создаваться дополнительный JOIN до Value таблицы
             */
            $conditions = [];
            foreach ($fields as $code => $value) {
                $conditions[] = new QAnd([
                    'q__attribute_code' => $code,
                    'q__value_id' => $value->id,
                ]);
            }

            $qs->filter(new QOr($conditions));

            $qs
                ->group(['product_value_variant_1.variant_id'])
                // ->having(sprintf('COUNT(product_value_variant_1.variant_id) = %s', count($fields)));
                ->having(['count' => count($fields)]);
        }

        /*
        $qb = $qs->getQueryBuilder();

        $ownerAlias = $qs->getTableAlias();
        $i = 0;
        foreach (array_filter($data) as $key => $value) {
            $alias = sprintf('v%s', $i);
            if (is_array($value)) {
                $sql = strtr("[[{ownerAlias}]].[[id]]=[[{alias}]].[[variant_id]] AND [[{alias}]].[[attribute_code]]='{code}' AND [[{alias}]].[[value_id]] IN {value}", [
                    '{ownerAlias}' => $ownerAlias,
                    '{alias}' => $alias,
                    '{code}' => $key,
                    '{value}' => implode(',', array_map(function ($item) {
                        return sprintf("'%s'", $item instanceof ModelInterface ? $item->pk : $item);
                    }, $value)),
                ]);
            } else {
                $sql = strtr("[[{ownerAlias}]].[[id]]=[[{alias}]].[[variant_id]] AND [[{alias}]].[[attribute_code]]='{code}' AND [[{alias}]].[[value_id]]='{value}'", [
                    '{ownerAlias}' => $ownerAlias,
                    '{alias}' => $alias,
                    '{code}' => $key,
                    '{value}' => $value instanceof ModelInterface ? $value->pk : $value,
                ]);
            }
            $qb->join('INNER JOIN', ValueVariant::tableName(), $sql, $alias);
            ++$i;
        }

        $qs->setSql($qb->toSQL());
        */
    }

    public function indexAction(Request $request)
    {
        $qs = Variant::objects()->published()->master();

        if ($request->query->has('order')) {
            $orderAttrs = [
                '',
                'price',
                '-price',
            ];
            $sourceOrder = $request->query->get('order');
            if (false === empty($sourceOrder) && in_array($sourceOrder, $orderAttrs)) {
                $qs->order([
                    $sourceOrder,
                ]);
            }
        }

        $filter = $request->query->get('filter', []);
        $filterForm = $this->createForm(FilterFormType::class, $filter);
        $filterForm->handleRequest($request);
        if ($filterForm->isSubmitted()) {
            $this->filterQuerySet($filterForm->getData(), $qs);
        }

        $pager = $this->createPagination($qs, [
            'pageSize' => 12,
        ]);

        return $this->render('rise/product/list.html', [
            'variants' => $pager->paginate(),
            'pager' => $pager->createView(),
            'filterForm' => $filterForm->createView(),
        ]);
    }

    public function viewAction($slug)
    {
        $variant = Variant::objects()->published()->get(['slug' => $slug]);
        if ($variant === null) {
            throw new NotFoundHttpException();
        }

        $product = $variant->product;
        $similarIds = $product->objects()->related()->limit(10)->valuesList(['id'], true);
        if (count($similarIds) > 0) {
            $similar = Variant::objects()
                ->filter(['product_id__in' => $similarIds])
                ->exclude(['id' => $variant->id])
                ->all();
        } else {
            $similar = [];
        }

        $relatedProducts = $product->related->valuesList(['id'], true);
        if (count($relatedProducts) > 0) {
            $related = Variant::objects()->filter([
                'is_master' => true,
                'product__id__in' => $relatedProducts,
            ])->exclude([
                'id' => $variant->id,
            ])->all();
        } else {
            $related = [];
        }

        $variants = $product
            ->variants
            ->exclude([
                'id' => $variant->id,
            ])
            ->all();

        return $this->render('rise/product/view.html', [
            'product' => $product,
            'variant' => $variant,
            'variants' => $variants,
            'images' => $product->getImages(),
            'similar' => $similar,
            'related' => $related,
            'values' => $variant->getValues(),
        ]);
    }
}
