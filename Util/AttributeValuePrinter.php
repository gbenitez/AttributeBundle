<?php

namespace gbenitez\Bundle\AttributeBundle\Util;

use gbenitez\Bundle\AttributeBundle\Entity\Attribute;
use gbenitez\Bundle\AttributeBundle\Entity\AttributeValueInterface;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class AttributeValuePrinter
{
    /**
     * @var \Twig_Environment
     */
    private $twig;
    private $datetimeFormat;
    private $arrayJoinSeparator;

    private $validTemplates = [
        'checkbox' => 'checkbox',
        'choice' => 'choice',
        'money' => 'number',
        'number' => 'number',
        'percent' => 'percent',
        'entity' => 'entity',
        'date' => 'datetime',
        'datetime' => 'datetime',
        'time' => 'datetime',
    ];

    /**
     * AttributeValuePrinter constructor.
     * @param $datetimeFormat
     * @param $arrayJoinSeparator
     */
    public function __construct(\Twig_Environment $twig, $datetimeFormat, $arrayJoinSeparator = ', ')
    {
        $this->twig = $twig;
        $this->datetimeFormat = $datetimeFormat;
        $this->arrayJoinSeparator = $arrayJoinSeparator;
    }

    public function toString(AttributeValueInterface $attributeValue)
    {
        $value = $attributeValue->getValue();
        $attribute = $attributeValue->getAttribute();

        $template = $this->twig->resolveTemplate([
            $this->resolveTemplate($attribute),
            $this->getTwigTemplateName('text') //Si no existe el template especifico, usamo el por defecto
        ]);

        return $template->render([
            'value' => $value,
            'attribute' => $attribute,
            'attribute_value' => $attributeValue,
            'configuration' => $attribute->getConfiguration(),
        ]);
    }

    /**
     * @return string
     */
    private function resolveTemplate(Attribute $attribute)
    {
        $templateName = $attribute->getValueTemplate();

        if (null === $templateName) {
            if (isset($this->validTemplates[$attribute->getType()])) {
                $templateName = $this->validTemplates[$attribute->getType()];
            } else {
                $templateName = 'text';
            }
        }

        return $this->getTwigTemplateName($templateName);
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