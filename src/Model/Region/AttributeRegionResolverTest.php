<?php
/*
 * This file is part of the Manuel Aguirre Project.
 *
 * (c) Manuel Aguirre <programador.manuel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gbenitez\Bundle\AttributeBundle\Model\Region;

use Gbenitez\Bundle\AttributeBundle\Entity\Attribute;
use Gbenitez\Bundle\AttributeBundle\Entity\AttributeValueInterface;
use Gbenitez\Bundle\AttributeBundle\Entity\Region;
use PHPUnit_Framework_TestCase;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class AttributeRegionResolverTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var AttributeRegionResolver
     */
    private $resolver;

    public function setUp()
    {
        $this->resolver = new AttributeRegionResolver();
    }

    public function test()
    {
        $regions = ['a', 'b'];

        $a = $this->createAttributeValue('a');
        $b = $this->createAttributeValue('b');
        $c = $this->createAttributeValue('c');

        $attributes = [$a, $b, $c];

        $result = $this->resolver->getValuesByRegions($regions, $attributes);

        $this->assertSame($result, [$a, $b]);
    }

    private function createRegion($name)
    {
        return $this->prophesize(Region::class)
            ->getName()->willReturn($name)
            ->getObjectProphecy()
            ->reveal();
    }

    private function createAttribute($regionName)
    {
        return $this->prophesize(Attribute::class)
            ->getRegion()->willReturn($this->createRegion($regionName))
            ->getObjectProphecy()
            ->reveal();
    }

    private function createAttributeValue($regionName)
    {
        return $this->prophesize(AttributeValueInterface::class)
            ->getAttribute()->willReturn($this->createAttribute($regionName))
            ->getObjectProphecy()
            ->reveal();
    }
}
