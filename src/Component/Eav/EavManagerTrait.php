<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rise\Component\Eav;

use Mindy\Orm\ModelInterface;
use Rise\Bundle\ProductBundle\Model\ValueVariant;

/**
 * Class EavManagerTrait
 *
 * @property \Mindy\Orm\ManagerInterface $this
 */
trait EavManagerTrait
{
    public function eavFilter(array $parameters)
    {
        /** @var \Mindy\Orm\ManagerInterface $this */
        $qs = $this->getQuerySet();
        $qb = $qs->getQueryBuilder();

        $ownerAlias = $qs->getTableAlias();
        $i = 0;
        foreach (array_filter($parameters) as $key => $value) {
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
    }
}
