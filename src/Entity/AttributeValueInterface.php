<?php

namespace gbenitez\Bundle\AttributeBundle\Entity;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
interface AttributeValueInterface
{
    /**
     * @return Attribute
     */
    public function getAttribute();

    public function setAttribute(Attribute $attribute);

    public function getValue();

    public function setValue($value);
}
