<?php

use gbenitez\Bundle\AttributeBundle\Entity\Attribute;
use gbenitez\Bundle\AttributeBundle\Entity\AttributeValueInterface;
use gbenitez\Bundle\AttributeBundle\Util\AttributeValuePrinter;
use Prophecy\Argument;

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
        $this->twig = $this->prophesize(Twig_Environment::class);

        $this->twigTemplate = $this->prophesize(Twig_Template::class);
        $this->twigTemplate->render(Argument::any())->shouldBeCalled();

        $this->printer = new AttributeValuePrinter(
            $this->twig->reveal(),
            self::DATE_FORMAT,
            ', '
        );
    }

    public function printScalarDataProvider()
    {
        return [
            [1, 'number', 'number', 'other'],
            ['value', 'text', 'text', 'other'],
            ['value', 'choice', 'choice', 'other'],
            ['value', 'money', 'number', 'other'],
            ['value', 'percent', 'percent', 'other'],
            ['value', 'entity', 'entity', 'other'],
            ['value', 'date', 'datetime', 'other'],
            ['value', 'datetime', 'datetime', 'other'],
            ['value', 'time', 'datetime', 'other'],
        ];
    }

    /**
     * @dataProvider printScalarDataProvider
     */
    public function testScalarValueWithoutTemplateValueSetted($value, $type, $template)
    {
        $attrValue = $this->createAttributeValueMock($value, $type);

        $this->twig->resolveTemplate($this->createResolveTemplateArgument($template))
            ->willReturn($this->twigTemplate->reveal())
            ->shouldBeCalled();

        $this->twigTemplate->render(Argument::any())->willReturn($value);

        $this->assertSame($value, $this->printer->toString($attrValue->reveal()));
    }

    /**
     * @dataProvider printScalarDataProvider
     */
    public function testScalarValueWithTemplateValueSetted($value, $type, $template, $ownTemplate)
    {
        $attrValue = $this->createAttributeValueMock($value, $type, $ownTemplate);

        $this->twig->resolveTemplate($this->createResolveTemplateArgument($ownTemplate))
            ->willReturn($this->twigTemplate->reveal())
            ->shouldBeCalled();

        $this->twigTemplate->render(Argument::any())->willReturn($value);

        $this->assertSame($value, $this->printer->toString($attrValue->reveal()));
    }

    /**
     * @param $value
     * @param $templateName
     * @return AttributeValueInterface|\Prophecy\Prophecy\ObjectProphecy
     */
    private function createAttributeValueMock($value, $type, $templateName = null, &$attr = null)
    {
        $attr = $this->prophesize(Attribute::class);
        $attr->getType()->willReturn($type);
        $attr->getValueTemplate()->willReturn($templateName);
        $attr->getConfiguration()->willReturn([]);

        $attrValue = $this->prophesize(AttributeValueInterface::class);

        $attrValue->getValue()->willReturn($value);
        $attrValue->getAttribute()->willReturn($attr->reveal());

        return $attrValue;
    }

    /**
     * @param $template
     * @return array
     */
    private function createResolveTemplateArgument($template)
    {
        return [
            '@Attribute/field/'.$template.'.html.twig',
            '@Attribute/field/text.html.twig',
        ];
    }
}