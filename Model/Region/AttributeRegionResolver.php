<?php

namespace gbenitez\Bundle\AttributeBundle\Model\Region;

use gbenitez\Bundle\AttributeBundle\Entity\AttributeValueInterface;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class AttributeRegionResolver
{

    public function getByRegions($regions, $attributes)
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