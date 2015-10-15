<?php

namespace gbenitez\Bundle\AttributeBundle\Form\Type;

use AttributeBundle\Entity\Attribute;
use AttributeBundle\Entity\AttributeValueInterface;
use AttributeBundle\Entity\Repository\AttributeRepository;
use AttributeBundle\Model\AttributeTypes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class AttributesType extends AbstractType
{

    public function getName()
    {
        return 'attributes';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            if(null === $data){
                return;
            }

            /** @var AttributeValueInterface $attributeValue */
            foreach ($data as $index => $attributeValue) {
                $attribute = $attributeValue->getAttribute();
                $form->add($attribute->getName(), 'attribute_value', array(
                    'data_class' => get_class($attributeValue),
                    'label' => $attribute->getPresentation(),
                    'data' => $attributeValue,
                    'property_path' => '['. $index .']',
                ));
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'global_options' => array(),
        ));
    }
}