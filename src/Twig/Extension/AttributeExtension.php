<?php

namespace Gbenitez\AttributeBundle\Twig\Extension;

use Gbenitez\AttributeBundle\Entity\AttributeValueInterface;
use Gbenitez\AttributeBundle\Form\Region\AttributeRegionFilter;
use Gbenitez\AttributeBundle\Model\Region\AttributeRegionResolver;
use Gbenitez\AttributeBundle\Util\AttributeValuePrinter;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\TwigTest;
//use function gbenitez\Bundle\AttributeBundle\Twig\Extension\strtr;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class AttributeExtension extends AbstractExtension
{
    private AttributeValuePrinter $printer;
    private AttributeRegionResolver $regionResolver;
    private AttributeRegionFilter $regionFilter;

    /**
     * AttributeExtension constructor.
     * @param AttributeValuePrinter $printer
     * @param AttributeRegionFilter $regionFilter
     * @param AttributeRegionResolver $regionResolver
     */
    public function __construct(AttributeValuePrinter $printer,AttributeRegionFilter $regionFilter, AttributeRegionResolver $regionResolver)
    {

        $this->printer = $printer;
        $this->regionResolver = $regionResolver;
        $this->regionFilter = $regionFilter;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'gbenitez_attributes';
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('form_attributes', [$this, 'filterFormAttributesByRegion']),
            new TwigFunction('attributes_by_region', [$this, 'getAttributesByRegion']),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('attribute_value', [$this, 'getAttributeValueAsString'], [
                'is_safe' => ['html'],
                'needs_environment' => true,
            ]),
        ];
    }

    public function filterFormAttributesByRegion($forms, $region)
    {
        return $this->regionFilter->getAttributesByRegion($forms, $region);
    }

    /**
     * @param $regions
     * @param $attributes
     * @return array|AttributeValueInterface[]
     */
    public function getAttributesByRegion($regions, $attributes)
    {
        return $this->regionResolver->getValuesByRegions($regions, $attributes);
    }

    public function getAttributeValueAsString(
        \Twig\Environment $twig,
        AttributeValueInterface $value,
        $context = null,
        $wrap = 'span',
        $javascript = true
    ) {
        $content = $this->printer->toString($value, $context);
        $attribute = $value->getAttribute();

        if (null != $wrap) {
            $content = strtr('<{wrap} data-attribute-name="{name}">{content}</{wrap}>', [
                '{wrap}' => $wrap,
                '{name}' => $attribute->getName(),
                '{content}' => $content,
            ]);
        }

        if ($javascript && null != trim($attribute->getJavascriptCode())) {
            $content .= $twig->render('@GbenitezAttribute/_attribute_javascript.html.twig', [
                'context' => $context,
                'attribute' => $attribute,
            ]);
        }

        return $content;
    }
}
