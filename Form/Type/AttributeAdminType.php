<?php
/**
 * Created by
 * User: gabriel
 * Date: 11-10-2015
 * Time: 12:52 AM
 */

namespace gbenitez\Bundle\AttributeBundle\Form\Type;

use gbenitez\Bundle\AttributeBundle\Model\AttributeTypes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class AttributeAdminType extends AbstractType
{

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'attribute_admin_filter';
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'required' => false,
                'label' => 'Name',
            ))
            ->add('presentation', 'text', array(
                'required' => false,
                'label' => 'Presentation',
            ))
            ->add('type', 'choice', array(
                'label' => 'Type',
                'choices' => AttributeTypes::getChoices(),
                'placeholder' => false
            ))
            ->add('targetEntity', null, array(
                'label' => 'Target Entity',
            ))
            ->add('active', 'choice', array(
                'choices' => array(
                    1 => 'Yes',
                    0 => 'No'
                ),
                'multiple' => false,
                'expanded' => true

            ))
            ->add('configuration', 'yaml', array(
                'required' => false,
            ))
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'gbenitez\Bundle\AttributeBundle\Entity\Attribute',
        ));
    }
}