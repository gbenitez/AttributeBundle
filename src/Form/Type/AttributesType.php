<?php

namespace Gbenitez\AttributeBundle\Form\Type;

use Gbenitez\AttributeBundle\Entity\AttributeValueInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class AttributesType extends AbstractType
{

    public function getBlockPrefix()
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
                $form->add($attribute->getName(), AttributeValueType::class, [
                    'data_class' => get_class($attributeValue),
                    'label' => $attribute->getPresentation(),
                    'data' => $attributeValue,
                    'property_path' => '['. $index .']',
                    'attribute' => $attribute,
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'global_options' => [],
        ]);
    }
}
