<?php

namespace FHV\Bundle\TmdBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Repository for results
 * Class ResultRepository
 * @package FHV\Bundle\TmdBundle\Entity
 */
class ResultRepository extends EntityRepository
{
    /**
     * Finds results by criteria and counts them
     *
     * @param array $criteria
     *
     * @return integer
     */
    public function findByAndCount(array $criteria)
    {
        $qb = $this->createQueryBuilder('results');
        $this->addSimpleWhereStatements($qb, $criteria);
        $qb->select('COUNT(results)');

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Adds where statements according to criteria
     *
     * @param QueryBuilder $qb
     * @param array        $criteria
     */
    protected function addSimpleWhereStatements(QueryBuilder $qb, array $criteria){
        foreach($criteria as $key => $value){
            if($value === null) {
                $qb->andWhere('results.'.$key.' IS NULL');
            } else {
                $qb->andWhere('results.'.$key.' = :'.$key);
                $qb->setParameter($key, $value);
            }
        }
    }
}
