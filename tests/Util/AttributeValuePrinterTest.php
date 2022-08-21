<?php

namespace Util;

use Gbenitez\AttributeBundle\Entity\Attribute;
use Gbenitez\AttributeBundle\Entity\AttributeValueInterface;
use Gbenitez\AttributeBundle\Util\AttributeValuePrinter;
use Gbenitez\AttributeBundle\Util\DateTime;
use PHPUnit_Framework_TestCase;
use Prophecy\Argument;
use Twig_Environment;
use Twig_Loader_Array;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class AttributeValuePrinterTest extends PHPUnit_Framework_TestCase
{
    const DATE_FORMAT = DateTime::W3C;
    private $twigTemplate;

    /**
     * @var AttributeValuePrinter
     */
    private $printer;
    private $twig;

    public function setUp()
    {
        $this->twig = new Twig_Environment(new Twig_Loader_Array([]));
        $this->twig->getExtension('core')->setDateFormat(self::DATE_FORMAT);

//        $this->twigTemplate = $this->prophesize(Twig_Template::class);
//        $this->twigTemplate->render(Argument::any())->shouldBeCalled();

        $this->printer = new AttributeValuePrinter(
            $this->twig,
            self::DATE_FORMAT,
            ', '
        );
    }

    public function dataProviderWithoutTemplate()
    {
        return [
            ['test'],
            ['123'],
            [new DateTime('now')],
        ];
    }

    public function dataProviderWithTemplate()
    {
        return [
            ['test', '{{ value }} value', 'test value'],
            ['test', '{{ value|upper }}', 'TEST'],
            ['123', '{{ value }}', '123'],
            [$v = new DateTime('now'), '{{ value|date }}', $v->format(self::DATE_FORMAT)],
            [$v = new DateTime('now'), '{{ value.format("Y") }}', $v->format('Y')],
        ];
    }

    /**
     * @dataProvider dataProviderWithoutTemplate
     */
    public function testScalarValueWithoutTemplateValueSetted($value)
    {
        $attrValue = $this->createAttributeValueMock($value);

        $this->assertSame($value, $this->printer->toString($attrValue->reveal()));
    }

    /**
     * @dataProvider dataProviderWithTemplate
     */
    public function testScalarValueWithTemplateValueSetted($value, $template, $expected)
    {
        $attrValue = $this->createAttributeValueMock($value, $template);

        $this->assertSame($expected, $this->printer->toString($attrValue->reveal()));
    }

    /**
     * @param $value
     * @param $templateName
     * @return AttributeValueInterface|\Prophecy\Prophecy\ObjectProphecy
     */
    private function createAttributeValueMock($value, $templateName = null, &$attr = null)
    {
        $attr = $this->prophesize(Attribute::class);
        $attr->getValueTemplate()->willReturn($templateName);
        $attr->getConfiguration()->willReturn([]);

        $attrValue = $this->prophesize(AttributeValueInterface::class);

        $attrValue->getValue()->willReturn($value);
        $attrValue->getAttribute()->willReturn($attr->reveal());

        return $attrValue;
    }
}
