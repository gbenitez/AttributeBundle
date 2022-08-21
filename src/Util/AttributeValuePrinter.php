<?php

namespace Gbenitez\Bundle\AttributeBundle\Util;

use Gbenitez\Bundle\AttributeBundle\Entity\AttributeValueInterface;
use Gbenitez\Bundle\AttributeBundle\Util\Twig;
use Twig\Twig_Error;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class AttributeValuePrinter
{



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
        } catch (Twig\Twig_Error $e) {
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
