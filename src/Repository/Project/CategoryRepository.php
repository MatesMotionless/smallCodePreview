<?php

namespace App\Repository\Project;

use App\Entity\Project\Category;
use App\Entity\Project\CategoryCategory;
use App\Entity\Project\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Category::class);
        $this->entityManager = $entityManager;
    }


    public function getProductCategoriesForIndexing(Product $product){
        return $this->createQueryBuilder('c')
            ->leftJoin('c.categoryProducts', 'cp')
            ->select('c.id','c.name', 'c.menu_name')
            ->andWhere('cp.product = :product')->setParameter('product', $product)
            ->andWhere('c.isActive = 1')
            ->distinct()
            ->getQuery()->getArrayResult();
    }
}
