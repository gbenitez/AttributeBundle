<?php
/**
 * Created by
 * User: gabriel
 * Date: 11-10-2015
 * Time: 02:06 PM
 */

namespace gbenitez\Bundle\AttributeBundle\Model;


use AttributeBundle\Entity\Attribute;
use Doctrine\ORM\EntityManager;

class AttributeManager
{
    /**
     * @var EntityManager
     */
    protected $em;


    public function __construct($em)
    {
        $this->em = $em;
    }

    public function saveAttribute(Attribute $Point)
    {

        $this->em->persist($Point);
        $this->em->flush();

        return true;
    }
}