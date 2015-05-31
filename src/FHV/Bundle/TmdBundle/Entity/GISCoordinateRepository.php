<?php

namespace FHV\Bundle\TmdBundle\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use FHV\Bundle\TmdBundle\Model\BoundingBox;

/**
 * Repository for gis coordinates
 * Class GISCoordinateRepository
 * @package FHV\Bundle\TmdBundle\Entity
 */
class GISCoordinateRepository extends EntityRepository
{
    /**
     * Returns coordinates for a type within a bounding box
     * @param BoundingBox $boundingBox
     * @param string $type
     * @return mixed
     */
    public function getCoordinatesForBoundingBox(BoundingBox $boundingBox, $type = 'busstop')
    {
        $qb = $this->createQueryBuilder('coordinate')
            ->andWhere('coordinate.type = :type');

        $this->addWhereForBoundingBox($qb, $boundingBox);
        $qb->setParameter('type', $type);

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * Adds where statements to a query builder for a bounding box
     * @param QueryBuilder $qb
     * @param BoundingBox $bb
     */
    protected function addWhereForBoundingBox(QueryBuilder $qb, BoundingBox $bb)
    {
        $qb->andWhere('coordinate.latitude <= :top');
        $qb->andWhere('coordinate.latitude >= :bottom');
        $qb->andWhere('coordinate.longitude >= :left');
        $qb->andWhere('coordinate.longitude <= :right');

        $qb->setParameter('top', $bb->getTop());
        $qb->setParameter('bottom', $bb->getBottom());
        $qb->setParameter('left', $bb->getLeft());
        $qb->setParameter('right', $bb->getRight());
    }
}
