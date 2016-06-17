<?php

namespace gbenitez\Bundle\AttributeBundle\Entity\Repository;


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

    /**
     * @param string $name
     * @return array
     */
    public function findActiveAttributesByEntity($name)
    {
        return $this->findBy([
            'active' => true,
            'targetEntity' => $name,
        ]);
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