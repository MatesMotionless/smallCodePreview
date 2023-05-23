<?php

namespace App\Repository\Project;

use App\Entity\Project\Category;
use App\Entity\Project\Parameter;
use App\Entity\Project\ParameterGroup;
use App\Entity\Project\ParameterGroupType;
use App\Entity\Project\Product;
use App\Entity\Project\ProductVariant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Workflow\Exception\NotEnabledTransitionException;

/**
 * @extends ServiceEntityRepository<Parameter>
 *
 * @method Parameter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Parameter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Parameter[]    findAll()
 * @method Parameter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParameterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Parameter::class);
    }

    public function getParametersForProductIndex(ProductVariant $productVariant){
        $results =  $this->createQueryBuilder('p')
            ->select('p.id', 'p.data', 'pg.name, pg.unit')
            ->leftJoin('p.parameterGroup', 'pg')
            ->andWhere('p.productVariant = :variant')->setParameter('variant', $productVariant)
            ->andWhere('pg.isProductParameter = 1')
            ->groupBy('p.data', 'pg.name')
            ->getQuery()->getArrayResult();

        foreach ($results as &$result) {
            if ($result['unit'] === null) {
                unset($result['unit']);
            }
        }

        return $results;
    }

}
