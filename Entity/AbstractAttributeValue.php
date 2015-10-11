<?php

namespace AttributeBundle\Entity;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class AbstractAttributeValue implements AttributeValueInterface
{
    /**
     * @var Attribute
     */
    protected $attribute;
    protected $value;

    /**
     * @return mixed
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