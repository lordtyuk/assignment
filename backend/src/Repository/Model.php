<?php

namespace App\Repository;

use Doctrine\Orm\EntityRepository;

class Model extends EntityRepository
{
    public function getByFilters(
        $name,
        $ramArray,
        $storageType,
        $storageSizeMin,
        $storageSizeMax,
        $location
    )
    {
        $qb = $this->createQueryBuilder('m')
            ->join('m.ramType', 'r')
            ->join('m.storageType', 's')
            ->join('m.location', 'l');

        if ($name) {
            $qb->andWhere('LOWER(m.name) LIKE :lname')
                ->setParameter('lname', '%'.strtolower($name).'%');
        }

        if ($location) {
            $qb->andWhere('LOWER(l.name) LIKE :name')
                ->setParameter('name', '%'.strtolower($name).'%');
        }

        return $qb->getQuery()->getResult();
    }
}
