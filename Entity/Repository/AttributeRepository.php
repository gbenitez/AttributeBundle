<?php

namespace gbenitez\Bundle\AttributeBundle\Entity\Repository;


use Doctrine\ORM\EntityRepository;
use gbenitez\Bundle\AttributeBundle\Entity\Attribute;

class AttributeRepository extends EntityRepository
{
    public function getQueryAll($filters = array())
    {
        $q = $this->createQueryBuilder('attribute');

        if (isset($filters['search'])) {
            $q->andWhere('attribute.name = :search')
                ->setParameter('search', $filters['search']);
        }


        return $q->getQuery()->getResult();
    }

    /**
     * @param string $name
     * @return array
     */
    public function findActiveAttributesByEntity($name)
    {
        return $this->findBy([
            'active' => true,
            'targetEntity' => $name,
        ], ['position' => 'ASC']);
    }

    /**
     * @param $owner
     * @return array
     */
    public function findActiveAttributesByOwner($owner)
    {
        return $this->findBy([
            'active' => true,
            'owner' => $owner,
        ]);
    }

    /**
     * @param $name
     * @param $owner
     * @return array
     */
    public function findActiveAttributesByEntityAndOwner($name, $owner)
    {
        return $this->findBy([
            'active' => true,
            'targetEntity' => $name,
            'owner' => $owner
        ]);
    }
}
