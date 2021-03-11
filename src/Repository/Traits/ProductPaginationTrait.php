<?php


namespace App\Repository\Traits;


use App\Model\ProductQuery;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;

trait ProductPaginationTrait
{
    public function getListOffset(ProductQuery $options): array {
        $alias = self::RESULTS_ALIAS;

        $qb = $this->getFilteredQb($options, $alias)
            ->setFirstResult($options->getOffset());

        $this->sortQb($qb, $options, $alias);

        return ['data' => $qb->getQuery()->getArrayResult(), 'rows' => $this->getTotalResultCount($options)];
    }
    public function getListWhere(ProductQuery $options, bool $lt): array {
        if(!$options->getPivot()) {
            return $this->getListOffset($options);
        }

        $alias = self::RESULTS_ALIAS;

        $qb = $this->getFilteredQb($options, $alias);

        if($lt) {
            $reverse = $this->paginateLT($options, $qb, $alias);
        } else {
            $reverse = $this->paginateGT($options, $qb, $alias);
        }

        $this->sortQb($qb, $options, $alias);

        if($reverse) {
            $result = array_reverse($qb->getQuery()->getArrayResult());
        } else {
            $result =  $qb->getQuery()->getArrayResult();
        }
        return ['data' => $result, 'rows' => $this->getTotalResultCount($options)];
    }

    private function paginateGt(ProductQuery $options,QueryBuilder $qb, $alias):bool {

        $reverse = false;
        if($options->getOrderSort() == "DESC") {
            $options->setOrderSort("ASC");
            $reverse = true;
        }
        $qb->andWhere($qb->expr()->gt($alias.".".$options->getOrderField(), ":pivot"))
            ->setParameter(":pivot", $options->getPivot());

        return $reverse;
    }

    private function paginateLT(ProductQuery $options, QueryBuilder $qb, $alias):bool {
        $reverse = false;
        if($options->getOrderSort() == "ASC") {
            $options->setOrderSort("DESC");
            $reverse = true;
        }

        $qb->andWhere($qb->expr()->lt($alias.".".$options->getOrderField(), ":pivot"))
            ->setParameter(":pivot", $options->getPivot());

        return $reverse;
    }

    private function getFilteredQb(ProductQuery $options, string $alias): QueryBuilder {
        $qb = $this->createQueryBuilder($alias);
        if($options->getName()) {
            $qb->andWhere($qb->expr()->like($alias.".name", ":name"))
                ->setParameter("name", '%'.$options->getName().'%');
        }
        if($options->getPriceGt()) {
            $qb->andWhere($qb->expr()->gt($alias.".price", ":priceGt"))
                ->setParameter("priceGt", $options->getPriceGt());
        }
        if($options->getPriceLt()) {
            $qb->andWhere($qb->expr()->lt($alias.".price", ":priceLt"))
                ->setParameter("priceLt", $options->getPriceLt());
        }
        if($options->getLimit() != 0) {
            $qb->setMaxResults($options->getLimit());
        }
        $qb->andWhere($qb->expr()->eq($alias.".isDeleted", ":isDeleted"))
            ->setParameter("isDeleted", false);
        return $qb;
    }
    private function sortQb(QueryBuilder $qb, ProductQuery $options, $alias): void {
        $qb->orderBy($alias.".".$options->getOrderField(), $options->getOrderSort());
    }
    private function getTotalResultCount(ProductQuery $options): int {
        try {
            $rows = $this->getFilteredQb($options, self::TOTAL_RESULTS_ALIAS)
                ->select("count('". self::TOTAL_RESULTS_ALIAS .".id')")
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException | NonUniqueResultException $ex) {
            $rows = 0;
        }
        return $rows;
    }
}
