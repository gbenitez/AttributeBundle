<?php
/**
 * Created by
 * User: gabriel
 * Date: 11-10-2015
 * Time: 12:52 AM
 */

namespace gbenitez\Bundle\AttributeBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use gbenitez\Bundle\AttributeBundle\Entity\Region;
use gbenitez\Bundle\AttributeBundle\Model\AttributeTypes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

//use Symfony\Component\Validator\Tests\Fixtures\Entity;


class AttributeAdminType extends AbstractType
{
    public function getBlockPrefix()
    {
        return 'attribute_admin_filter';
    }
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
            ->add('configuration', YamlType::class , array(
                'required' => false,
            ))
            ->add('region', EntityType::class, array(
                'label' => 'Region:',
                'class' => Region::class,
                'choice_label' => 'name',
                'placeholder' => 'Seleccione',
                'required' => true,
                'invalid_message' => 'Debe seleccionar un fabricante',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->where('r.active = 1');
                }
            ))

        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'gbenitez\Bundle\AttributeBundle\src\Entity\Attribute',
        ));
    }
}
