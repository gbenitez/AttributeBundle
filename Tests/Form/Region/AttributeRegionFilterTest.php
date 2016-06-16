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
use gbenitez\Bundle\AttributeBundle\Form\Region\AttributeRegionFilter;
use Symfony\Component\Form\FormView;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class AttributeRegionFilterTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var AttributeRegionFilter
     */
    private $filter;

    public function setUp()
    {
        $this->filter = new AttributeRegionFilter();
    }

    public function testGetAttributesByRegion()
    {
        $form = $this->createFormView([
            'name1' => $f1 = $this->createAttributeValueFormView('a'),
            'name2' => $this->createFormView(),
            'name3' => $this->createFormView(),
            'name4' => $this->createFormView([
                'name5' => $f2 = $this->createAttributeValueFormView('a'),
                'name6' => $f3 = $this->createAttributeValueFormView('a'),
                'name7' => $f4 = $this->createAttributeValueFormView('b'),
                'name8' => $f5 = $this->createAttributeValueFormView('b'),
                'name9' => $f6 = $this->createAttributeValueFormView('c'),
            ]),
        ]);

        $forms = $this->filter->getAttributesByRegion($form, 'a');
        $this->assertCount(3, $forms);
        $this->assertSame([$f1, $f2, $f3], $forms);

        $forms = $this->filter->getAttributesByRegion($form, 'b');
        $this->assertCount(2, $forms);
        $this->assertSame([$f4, $f5], $forms);

        $forms = $this->filter->getAttributesByRegion($form, 'c');
        $this->assertCount(1, $forms);
        $this->assertSame([$f6], $forms);
    }

    /**
     * @param array $children
     * @return FormView
     */
    private function createFormView($children = [])
    {
        $form = new FormView();
        $form->children = $children;

        foreach ($children as $child) {
            $child->parent = $form;
        }

        return $form;
    }

    private function createAttributeValueFormView($regionName)
    {

        $attribute = $this->prophesize(Attribute::class)->reveal();

        $form = new FormView();
        $form->vars['_attribute'] = $attribute;
        $form->vars['_attribute_region_name'] = $regionName;

        return $form;
    }
}
