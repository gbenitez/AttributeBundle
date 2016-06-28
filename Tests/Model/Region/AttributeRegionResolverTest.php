<?php
/*
 * This file is part of the Manuel Aguirre Project.
 *
 * (c) Manuel Aguirre <programador.manuel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use gbenitez\Bundle\AttributeBundle\Entity\Attribute;
use gbenitez\Bundle\AttributeBundle\Entity\AttributeValueInterface;
use gbenitez\Bundle\AttributeBundle\Entity\Region;
use gbenitez\Bundle\AttributeBundle\Model\Region\AttributeRegionResolver;

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

        $result = $this->resolver->getByRegions($regions, $attributes);

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
