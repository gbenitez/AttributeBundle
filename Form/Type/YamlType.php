<?php

namespace gbenitez\Bundle\AttributeBundle\Form\Type;

use AttributeBundle\Form\DataTransformer\ArrayToYamlTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class YamlType extends AbstractType
{

    public function getName()
    {
        return 'yaml';
    }

    public function getParent()
    {
        return 'textarea';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new ArrayToYamlTransformer());
    }

}