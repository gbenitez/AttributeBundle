<?php

namespace gbenitez\Bundle\AttributeBundle\Twig\Extension;

use gbenitez\Bundle\AttributeBundle\Entity\AttributeValueInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\TwigTest;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class AttributeExtension extends AbstractExtension
{
    /**
     * @var AttributeValueInterface
     */
    private $value;

    /** @var \Twig\Environment */
    private $twig;

    /**
     * AttributeExtension constructor.
     * @param ContainerInterface $container
     */
    public function __construct(AttributeValueInterface $attributeValueInterface, \Twig\Environment $twig)
    {
        $this->value = $attributeValueInterface;
        $this->twig = $twig;
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
        return $this->container
            ->get('attribute.form.filter.attribute_region')
            ->getAttributesByRegion($forms, $region);
    }

    /**
     * @param $regions
     * @param $attributes
     * @return array|AttributeValueInterface[]
     */
    public function getAttributesByRegion($regions, $attributes)
    {
        return $this->container
            ->get('attribute.resolver.attribute_region')
            ->getValuesByRegions($regions, $attributes);
    }

    public function getAttributeValueAsString(
        $context = null,
        $wrap = 'span',
        $javascript = true
    ) {
        $content = $this->container->get('attribute.printer.attribute_value')->toString($this->value, $context);
        $attribute = $this->value->getAttribute();

        if (null != $wrap) {
            $content = strtr('<{wrap} data-attribute-name="{name}">{content}</{wrap}>', [
                '{wrap}' => $wrap,
                '{name}' => $attribute->getName(),
                '{content}' => $content,
            ]);
        }

        if ($javascript && null != trim($attribute->getJavascriptCode())) {
            $content .= $this->twig->render('@Attribute/_attribute_javascript.html.twig', [
                'context' => $context,
                'attribute' => $attribute,
            ]);
        }

        return $content;
    }
}
