<?php

namespace KaemoTestBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;


/**
 * MovieRepository
 *
 */
class MovieRepository extends EntityRepository
{
    public function search($params)
    {
        $qb = $this->createQueryBuilder('m');

        if(isset($params['realisator'])){
            $qb->andWhere('m.realisator LIKE :realisator')->setParameter('realisator', '%'.$params['realisator'].'%');
        }

        if(isset($params['from'])){
            $qb->andWhere('m.date >= :from')->setParameter('from', $params['from']);
        }

        if(isset($params['to'])){
            $qb->andWhere('m.date <= :to')->setParameter('to', $params['to']);
        }

        return $qb->getQuery()->getResult();
    }
}