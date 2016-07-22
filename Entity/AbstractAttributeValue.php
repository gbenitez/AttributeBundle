<?php

namespace gbenitez\Bundle\AttributeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 *
 * @ORM\MappedSuperclass()
 */
abstract class AbstractAttributeValue implements AttributeValueInterface
{
    /**
     * @var Attribute
     *
     * @ORM\ManyToOne(targetEntity="gbenitez\Bundle\AttributeBundle\Entity\Attribute", fetch="EAGER")
     */
    protected $attribute;

    /**
     * @var mixed
     *
     * @ORM\Column(name="value", type="array", length=255, nullable=true)
     */
    protected $value;

    /**
     * @return Attribute
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * @param Attribute $attribute
     */
    public function setAttribute(Attribute $attribute)
    {
        $this->attribute = $attribute;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}