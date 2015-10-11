<?php

namespace AttributeBundle\Entity\Repository;


use Doctrine\ORM\EntityRepository;

class AttributeRepository extends EntityRepository
{
    public function getQueryAll(array $filters = array())
    {
        $q = $this->createQueryBuilder('attribute');

        if (isset($filters['search'])) {
            $q->andWhere('attribute.name = :search')
                ->setParameter('search', $filters['search']);
        }


        return $q;
    }
}