<?php

namespace App\Repository;

use App\Entity\Product;
use App\Repository\Traits\ProductPaginationTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    const RESULTS_ALIAS = "p";
    const TOTAL_RESULTS_ALIAS = "p2";

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }
    public function delete(Product $product) {
        $product->setIsDeleted(true);
    }
    use ProductPaginationTrait;
}
