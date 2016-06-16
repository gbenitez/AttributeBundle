<?php

namespace gbenitez\Bundle\AttributeBundle\Twig\Extension;

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
            new \Twig_SimpleFunction('form_attributes', [$this, 'filterAttributesByRegion']),
        ];
    }

    public function filterAttributesByRegion($forms, $region)
    {
        return $this->container
            ->get('attribute.form.filter.attribute_region')
            ->getAttributesByRegion($forms, $region);
    }
}