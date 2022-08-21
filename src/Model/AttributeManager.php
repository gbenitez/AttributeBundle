<?php
/**
 * Created by
 * User: gabriel
 * Date: 11-10-2015
 * Time: 02:06 PM
 */

namespace Gbenitez\AttributeBundle\Model;


use Doctrine\ORM\EntityManager;
use Gbenitez\Bundle\AttributeBundle\Entity\Attribute;

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
