<?php

namespace Gbenitez\AttributeBundle\Model\Region;

use Gbenitez\AttributeBundle\Entity\Attribute;
use Gbenitez\AttributeBundle\Entity\AttributeValueInterface;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class AttributeRegionResolver
{

    /**
     * Devuelve un arreglo de objetos Attribute que se encuentran
     * definidos en las regiones indicadas.
     *
     * @param string|array $regions
     * @param array|Attribute[] $attributes
     * @return array|Attribute[]
     */
    public function getByRegions($regions, $attributes)
    {
        $result = [];
        $regions = (array)$regions;

        /** @var Attribute $attr */
        foreach ($attributes as $attr) {
            if (in_array($attr->getRegion()->getName(), $regions)) {
                $result[] = $attr;
            }
        }

        return $result;
    }

    /**
     * Devuelve un arreglo de objetos AttributeValueInterface que se encuentran
     * definidos en las regiones indicadas.
     *
     * @param string|array $regions
     * @param array|AttributeValueInterface[] $attributes
     * @return array|AttributeValueInterface
     */
    public function getValuesByRegions($regions, $attributes)
    {
        $result = [];
        $regions = (array)$regions;

        /** @var AttributeValueInterface $attr */
        foreach ($attributes as $attr) {
            if (in_array($attr->getAttribute()->getRegion()->getName(), $regions)) {
                $result[] = $attr;
            }
        }

        return $result;
    }
}
