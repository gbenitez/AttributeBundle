<?php

namespace gbenitez\Bundle\AttributeBundle\Util;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class AttributeValueArrayExtractor
{
    /**
     * @var AttributeValuePrinter
     */
    private $printer;

    /**
     * AttributeValueArrayExtractor constructor.
     * @param AttributeValuePrinter $printer
     */
    public function __construct(AttributeValuePrinter $printer)
    {
        $this->printer = $printer;
    }

    public function toArray($attributeValues, $context = null)
    {
        $values = [];

        foreach ($attributeValues as $attributeValue) {
            $id = $attributeValue->getAttribute()->getId();
            $values[$id] = $this->printer->toString($attributeValue, $context);
        }

        return $values;
    }
}