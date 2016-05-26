<?php

namespace gbenitez\Bundle\AttributeBundle\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class TargetEntityType extends AbstractType
{
    private $targetEntityNames = [];

    /**
     * TargetEntityType constructor.
     * @param array $targetEntityNames
     */
    public function __construct(array $targetEntityNames)
    {
        $this->targetEntityNames = $targetEntityNames;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'choices' => $this->targetEntityNames,
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

}