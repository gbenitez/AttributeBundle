<?php

namespace Gbenitez\AttributeBundle\Tests\Util;

use Gbenitez\AttributeBundle\Entity\Attribute;
use Gbenitez\AttributeBundle\Entity\AttributeValueInterface;
use Gbenitez\AttributeBundle\Util\AttributeValuePrinter;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class AttributeValuePrinterTest extends TestCase
{
    
    private const DATE_FORMAT = \DateTime::W3C;
    private $twigTemplate;
    private AttributeValuePrinter $printer;
    private Environment $twig;

    protected function setUp(): void
    {
        $this->twig = new Environment(new ArrayLoader([]));

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
            [new \DateTime('now')],
        ];
    }

    public function dataProviderWithTemplate()
    {
        return [
            ['test', '{{ value }} value', 'test value'],
            ['test', '{{ value|upper }}', 'TEST'],
            ['123', '{{ value }}', '123'],
            [$v = new \DateTime('now'), '{{ value|date }}', $v->format(self::DATE_FORMAT)],
            [$v = new \DateTime('now'), '{{ value.format("Y") }}', $v->format('Y')],
        ];
    }

    /**
     * @dataProvider dataProviderWithoutTemplate
     */
    public function testScalarValueWithoutTemplateValueSetted($value)
    {
        $attrValue = $this->createAttributeValueMock($value);

        $this->assertSame($value, $this->printer->toString($attrValue));
    }

    /**
     * @dataProvider dataProviderWithTemplate
     */
    public function testScalarValueWithTemplateValueSetted($value, $template, $expected)
    {
        $attrValue = $this->createAttributeValueMock($value, $template);

        $this->assertSame($expected, $this->printer->toString($attrValue));
    }

    /**
     * @param $value
     * @param $templateName
     * @return AttributeValueInterface|\Prophecy\Prophecy\ObjectProphecy
     */
    private function createAttributeValueMock($value, $templateName = null, &$attr = null)
    {
        $attr = $this->createMock(Attribute::class);
        $attr->method('getValueTemplate')->willReturn($templateName);
        $attr->method('getConfiguration')->willReturn([]);

        $attrValue = $this->createMock(AttributeValueInterface::class);
        $attrValue->method('getValue')->willReturn($value);
        $attrValue->method('getAttribute')->willReturn($attr);

        return $attrValue;
    }
}
