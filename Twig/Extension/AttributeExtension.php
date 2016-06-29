<?php

namespace gbenitez\Bundle\AttributeBundle\Twig\Extension;

use gbenitez\Bundle\AttributeBundle\Entity\AttributeValueInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class AttributeExtension extends \Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * AttributeExtension constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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
            new \Twig_SimpleFunction('form_attributes', [$this, 'filterFormAttributesByRegion']),
            new \Twig_SimpleFunction('attributes_by_region', [$this, 'getAttributesByRegion']),
        ];
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('attribute_value', [$this, 'getAttributeValueAsString'], [
                'is_safe' => ['html']
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
            ->getByRegions($regions, $attributes);
    }

    public function getAttributeValueAsString(AttributeValueInterface $value, $context = 'default')
    {
        return $this->container->get('attribute.printer.attribute_value')->toString($value, $context);
    }
}