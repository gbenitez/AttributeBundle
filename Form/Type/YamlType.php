<?php

namespace gbenitez\Bundle\AttributeBundle\Form\Type;

use gbenitez\Bundle\AttributeBundle\Form\DataTransformer\ArrayToYamlTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class YamlType extends AbstractType
{

    public function getBlockPrefix()
    {
        return 'yaml';
    }
    
    public function getName()
    {
        return 'yaml';
    }

    public function getParent()
    {
        return TextareaType::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new ArrayToYamlTransformer($options['inline_level']));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'inline_level' => 1000,
        ]);

        $resolver->setAllowedTypes('inline_level', 'numeric');
    }
}
