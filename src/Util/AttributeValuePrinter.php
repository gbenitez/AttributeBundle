<?php

namespace Gbenitez\AttributeBundle\Util;

use Gbenitez\AttributeBundle\Entity\AttributeValueInterface;
use Twig\Environment;
use Twig\Error\Error as TwigError;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class AttributeValuePrinter
{
    public function __construct(
        private readonly Environment $twig,
        private readonly string $dateFormat = \DateTime::W3C,
        private readonly string $separator = ', '
    ) {
    }



    public function toString(AttributeValueInterface $attributeValue, $context = null)
    {
        $value = $attributeValue->getValue();
        $attribute = $attributeValue->getAttribute();

        if (null === $attribute->getValueTemplate()) {
            return $value;
        }

        try {
            return $this->twig->createTemplate($attribute->getValueTemplate())->render([
                'value' => $value,
                'attribute' => $attribute,
                'attribute_value' => $attributeValue,
                'configuration' => $attribute->getConfiguration(),
                'context' => $context ? $context : 'default',
            ]);
        } catch (TwigError $e) {
            // TODO: crear un log
            return null;
        }
    }

    /**
     * @param $templateName
     * @return string
     */
    private function getTwigTemplateName($templateName)
    {
        return '@Attribute/field/'.$templateName.'.html.twig';
    }
}
